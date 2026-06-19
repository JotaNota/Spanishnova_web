#!/usr/bin/env python3
import argparse
import urllib.parse
from datetime import datetime, timezone

from spanishnova_upload.config import load_env
from spanishnova_upload.roadmap import find_row_by_slug, ensure_can_upload, read_roadmap_rows, write_roadmap_rows
from spanishnova_upload.uploader import audit_grammar, dry_run, find_html, grammar_order, sync_statuses, taxonomy_payload
from spanishnova_upload.wordpress import wp_request


UPLOAD_TRACKING_COLUMNS = [
    "wp_post_id",
    "wp_slug",
    "wp_status",
    "last_wp_upload_modified_gmt",
]


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


def normalize_wp_status(status):
    if status == "publish":
        return "published"
    return status or "planned"


def request_post_by_id(env, cpt, post_id):
    if not post_id:
        return None
    try:
        return wp_request(env, f"/wp-json/wp/v2/{cpt}/{post_id}?context=edit")
    except SystemExit:
        return None


def find_existing_post(env, row):
    cpt = row.get("cpt", "").strip()
    slug = row.get("base_slug", "").strip()
    title = row.get("public_title", "").strip()
    post_id = row.get("wp_post_id", "").strip()

    by_id = request_post_by_id(env, cpt, post_id)
    if by_id:
        return by_id

    if slug:
        query = urllib.parse.urlencode({"slug": slug, "status": "any", "per_page": 100, "context": "edit"})
        posts = wp_request(env, f"/wp-json/wp/v2/{cpt}?{query}")
        if posts:
            return posts[0]

    if title:
        query = urllib.parse.urlencode({"search": title, "status": "any", "per_page": 100, "context": "edit"})
        posts = wp_request(env, f"/wp-json/wp/v2/{cpt}?{query}")
        for post in posts:
            if post.get("title", {}).get("rendered", "").strip() == title:
                return post

    return None


def ensure_no_wp_overwrite_conflict(row, post):
    if not post:
        return

    slug = row.get("base_slug", "").strip()
    wp_post_id = post.get("id")
    wp_modified = parse_wp_datetime(post.get("modified_gmt") or post.get("modified"))
    last_upload = parse_wp_datetime(row.get("last_wp_upload_modified_gmt", ""))

    if not last_upload:
        raise SystemExit(
            "Blocked upload: "
            f"slug={slug} wp_post_id={wp_post_id} reason=existing WordPress post has no "
            "last_wp_upload_modified_gmt in roadmap; pull/reconcile before uploading."
        )

    if wp_modified and wp_modified > last_upload:
        raise SystemExit(
            "Blocked upload: "
            f"slug={slug} wp_post_id={wp_post_id} reason=WordPress modified_gmt "
            f"{post.get('modified_gmt')} is newer than last upload {row.get('last_wp_upload_modified_gmt')}."
        )


def update_roadmap_upload_tracking(slug, result):
    fieldnames, rows = read_roadmap_rows()
    if not fieldnames:
        raise SystemExit("Missing grammar roadmap header")

    updated_fieldnames = list(fieldnames)
    for column in UPLOAD_TRACKING_COLUMNS:
        if column not in updated_fieldnames:
            updated_fieldnames.append(column)

    updated = False
    for row in rows:
        if row.get("base_slug", "").strip() != slug:
            continue
        row["status"] = "draft"
        row["wp_post_id"] = str(result.get("id") or "")
        row["wp_slug"] = str(result.get("slug") or slug)
        row["wp_status"] = normalize_wp_status(result.get("status"))
        row["last_wp_upload_modified_gmt"] = str(result.get("modified_gmt") or result.get("modified") or "")
        updated = True
        break

    if not updated:
        raise SystemExit(f"Could not update roadmap upload tracking for: {slug}")

    write_roadmap_rows(updated_fieldnames, rows)


def protected_upload_one(env, row):
    ensure_can_upload(row)

    slug = row.get("base_slug", "").strip()
    html_path = find_html(row)
    if not html_path.exists():
        raise SystemExit(f"Missing HTML file: {html_path}")

    cpt = row.get("cpt", "").strip()
    title = row.get("public_title", "").strip()
    content = html_path.read_text(encoding="utf-8").strip()
    existing = find_existing_post(env, row)
    ensure_no_wp_overwrite_conflict(row, existing)

    payload = {
        "title": title,
        "slug": slug,
        "status": "draft",
        "content": content,
    }
    payload.update(taxonomy_payload(env, row))

    if existing:
        post_id = existing["id"]
        result = wp_request(env, f"/wp-json/wp/v2/{cpt}/{post_id}", method="POST", data=payload)
        print(f"Updated draft: {result.get('id')} {result.get('link')}")
    else:
        result = wp_request(env, f"/wp-json/wp/v2/{cpt}", method="POST", data=payload)
        print(f"Created draft: {result.get('id')} {result.get('link')}")

    update_roadmap_upload_tracking(slug, result)
    print(f"Updated roadmap upload tracking: {slug} -> draft")


def main():
    parser = argparse.ArgumentParser(description="Upload generated SpanishNova posts to Local WordPress.")
    parser.add_argument("--dry-run", action="store_true", help="Preview posts without uploading.")
    parser.add_argument("--check-auth", action="store_true", help="Check WordPress REST authentication.")
    parser.add_argument("--upload-one", action="store_true", help="Create or update one draft post. Requires --slug.")
    parser.add_argument("--sync-status", action="store_true", help="Update roadmap status from HTML files and WordPress.")
    parser.add_argument("--audit-grammar", action="store_true", help="Read-only reconciliation report for grammar roadmap, HTML, and WordPress.")
    parser.add_argument("--dry-run-grammar-order", action="store_true", help="Preview grammar menu_order changes from roadmap priority.")
    parser.add_argument("--sync-grammar-order", action="store_true", help="Update grammar menu_order from roadmap priority.")
    parser.add_argument("--slug", help="Only process one base_slug.")
    args = parser.parse_args()

    if args.check_auth:
        env = load_env()
        user = wp_request(env, "/wp-json/wp/v2/users/me")
        print(f"Connected as: {user.get('name')} ({user.get('slug')})")
        return

    if args.dry_run:
        dry_run(args.slug)
        return

    if args.upload_one:
        if not args.slug:
            raise SystemExit("--upload-one requires --slug")
        env = load_env()
        protected_upload_one(env, find_row_by_slug(args.slug))
        return

    if args.sync_status:
        env = load_env()
        sync_statuses(env)
        return

    if args.audit_grammar:
        env = load_env()
        audit_grammar(env)
        return

    if args.dry_run_grammar_order:
        env = load_env()
        grammar_order(sync=False, env=env)
        return

    if args.sync_grammar_order:
        env = load_env()
        grammar_order(sync=True, env=env)
        return

    raise SystemExit("Use --dry-run, --check-auth, --sync-status, --audit-grammar, --dry-run-grammar-order, --sync-grammar-order, or --upload-one --slug SLUG.")


if __name__ == "__main__":
    main()
