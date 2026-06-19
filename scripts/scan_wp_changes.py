#!/usr/bin/env python3
import argparse
import hashlib
import json
import urllib.parse
from datetime import datetime, timezone

from spanishnova_upload.config import HTML_ROOT, ROOT, load_env
from spanishnova_upload.roadmap import read_roadmap_rows
from spanishnova_upload.wordpress import wp_request
from spanishnova_render.grammar import render_html as render_grammar_html
from spanishnova_render.validation import validate_grammar_data


DATA_ROOT = ROOT / "docs/content-system/content-data"


def parse_wp_datetime(value):
    value = str(value or "").strip()
    if not value:
        return None
    if value.endswith("Z"):
        value = value[:-1] + "+00:00"
    if "+" not in value and value.count("-") == 2:
        value = f"{value}+00:00"
    try:
        parsed = datetime.fromisoformat(value)
    except ValueError:
        return None
    if parsed.tzinfo is None:
        parsed = parsed.replace(tzinfo=timezone.utc)
    return parsed.astimezone(timezone.utc)


def normalize_status(status):
    if status == "publish":
        return "published"
    return status or ""


def normalize_html(value):
    return str(value or "").replace("\r\n", "\n").strip()


def content_hash(value):
    return hashlib.sha256(normalize_html(value).encode("utf-8")).hexdigest()


def wp_content(post):
    content = post.get("content") or {}
    return content.get("raw") or content.get("rendered") or ""


def render_repo_html(row, json_path):
    if not json_path.exists():
        return None, "missing_json"
    try:
        data = json.loads(json_path.read_text(encoding="utf-8-sig"))
        validate_grammar_data(data, row.get("lesson_type", "").strip())
        return render_grammar_html(row, data), None
    except SystemExit as exc:
        return None, f"render_error={exc}"
    except Exception as exc:
        return None, f"render_error={exc}"


def load_wp_posts(env, cpt):
    posts = []
    page = 1
    while True:
        query = urllib.parse.urlencode(
            {"status": "any", "per_page": 100, "page": page, "context": "edit"}
        )
        batch = wp_request(env, f"/wp-json/wp/v2/{cpt}?{query}")
        if not batch:
            break
        posts.extend(batch)
        if len(batch) < 100:
            break
        page += 1
    return posts


def row_status(row, post, html_path, json_path):
    labels = []
    if post is None:
        return ["missing_wp"]

    wp_slug = str(post.get("slug") or "").strip()
    row_slug = row.get("base_slug", "").strip()
    row_title = row.get("public_title", "").strip()
    row_status_value = row.get("status", "").strip()
    wp_title = post.get("title", {}).get("rendered", "").strip()
    wp_status = normalize_status(post.get("status", ""))

    if wp_slug != row_slug:
        labels.append("slug_changed")
    if wp_title and wp_title != row_title:
        labels.append("title_changed")
    if wp_status and wp_status != row_status_value:
        labels.append("status_changed")

    repo_html, repo_error = render_repo_html(row, json_path)
    generated_html = html_path.read_text(encoding="utf-8") if html_path.exists() else ""
    repo_dirty = False
    if repo_error or not html_path.exists():
        repo_dirty = True
    elif content_hash(repo_html) != content_hash(generated_html):
        repo_dirty = True

    wp_dirty = False
    compare_html = repo_html if repo_html is not None else generated_html
    if compare_html:
        wp_dirty = content_hash(compare_html) != content_hash(wp_content(post))
    else:
        wp_dirty = bool(normalize_html(wp_content(post)))

    last_upload = parse_wp_datetime(row.get("last_wp_upload_modified_gmt", ""))
    wp_modified = parse_wp_datetime(post.get("modified_gmt") or post.get("modified"))
    if last_upload and wp_modified and wp_modified > last_upload:
        wp_dirty = True

    if wp_dirty and repo_dirty:
        labels.append("conflict")
    elif wp_dirty:
        labels.append("dirty_wp")
    elif repo_dirty:
        labels.append("dirty_repo")

    if not labels:
        labels.append("clean")
    return labels


def main():
    parser = argparse.ArgumentParser(description="Read-only WordPress vs repo scan for grammar content.")
    parser.add_argument("--type", default="grammar", choices=["grammar"], help="Content type to scan.")
    parser.add_argument("--slug", help="Only scan one roadmap base_slug.")
    args = parser.parse_args()

    env = load_env()
    fieldnames, rows = read_roadmap_rows()
    if not fieldnames:
        raise SystemExit("Missing grammar roadmap header")

    rows = [row for row in rows if row.get("cpt", "").strip() == args.type]
    if args.slug:
        rows = [row for row in rows if row.get("base_slug", "").strip() == args.slug]
        if not rows:
            raise SystemExit(f"Slug not found in roadmap: {args.slug}")

    wp_posts = load_wp_posts(env, args.type)
    posts_by_slug = {str(post.get("slug") or "").strip(): post for post in wp_posts if post.get("slug")}
    posts_by_id = {str(post.get("id") or "").strip(): post for post in wp_posts if post.get("id")}

    roadmap_slugs = {row.get("base_slug", "").strip() for row in rows if row.get("base_slug", "").strip()}
    counts = {}

    print("WordPress sync scan")
    print("===================")
    print(f"type: {args.type}")
    print(f"roadmap rows scanned: {len(rows)}")
    print(f"wordpress posts loaded: {len(wp_posts)}")
    print()

    for row in rows:
        slug = row.get("base_slug", "").strip()
        post = posts_by_id.get(row.get("wp_post_id", "").strip()) or posts_by_slug.get(slug)
        html_path = HTML_ROOT / args.type / f"{slug}.html"
        json_path = DATA_ROOT / args.type / f"{slug}.json"
        labels = row_status(row, post, html_path, json_path)
        for label in labels:
            counts[label] = counts.get(label, 0) + 1
        wp_id = post.get("id") if post else ""
        print(f"{','.join(labels)} slug={slug} wp_post_id={wp_id}")

    if not args.slug:
        for post in wp_posts:
            slug = str(post.get("slug") or "").strip()
            if slug and slug not in roadmap_slugs:
                counts["missing_repo"] = counts.get("missing_repo", 0) + 1
                print(f"missing_repo slug={slug} wp_post_id={post.get('id')}")

    print()
    print("Summary")
    print("-------")
    for label in [
        "clean",
        "dirty_wp",
        "dirty_repo",
        "conflict",
        "missing_wp",
        "missing_repo",
        "slug_changed",
        "title_changed",
        "status_changed",
    ]:
        print(f"{label}: {counts.get(label, 0)}")


if __name__ == "__main__":
    main()
