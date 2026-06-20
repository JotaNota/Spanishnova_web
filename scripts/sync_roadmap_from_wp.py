#!/usr/bin/env python3
import argparse
import html
import re
import sys
import urllib.parse

from spanishnova_upload.config import ROADMAPS, load_env
from spanishnova_upload.roadmap import read_roadmap_rows, write_roadmap_rows
from spanishnova_upload.wordpress import wp_request


METADATA_COLUMNS = ["route_tax", "route_block", "route_step", "wp_post_id", "wp_slug", "wp_status", "last_wp_seen_modified_gmt"]
VOCABULARY_OUTPUT_FOLDER = "docs/content-system/generated/generated-markdown-posts/vocabulary/"


def emit(message=""):
    encoding = sys.stdout.encoding or "utf-8"
    safe_message = str(message).encode(encoding, errors="replace").decode(encoding)
    print(safe_message)


def normalize_status(status):
    if status == "publish":
        return "published"
    return status or "planned"


def clean_title(post):
    title = post.get("title") or {}
    value = title.get("raw") or title.get("rendered") or ""
    value = re.sub(r"<[^>]+>", "", value)
    return html.unescape(value).strip()


def post_slug(post):
    return str(post.get("slug") or post.get("generated_slug") or "").strip()


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


def route_tax_slugs(env, post, cache):
    term_ids = post.get("route_tax") or []
    slugs = []

    for term_id in term_ids:
        key = str(term_id)
        if key not in cache:
            term = wp_request(env, f"/wp-json/wp/v2/route_tax/{term_id}")
            cache[key] = str(term.get("slug") or "").strip()
        if cache[key]:
            slugs.append(cache[key])

    return "; ".join(slugs)


def post_meta_value(post, key):
    meta = post.get("meta") or {}
    value = meta.get(key, "")
    return str(value or "").strip()


def add_columns(fieldnames):
    updated = list(fieldnames)
    for column in METADATA_COLUMNS:
        if column not in updated:
            updated.append(column)
    return updated


def update_row(row, updates):
    row_changes = []
    for key, value in updates.items():
        value = str(value or "")
        if row.get(key, "").strip() != value:
            row_changes.append(f"{key}: {row.get(key, '').strip()} -> {value}")
            row[key] = value
    return row_changes


def next_numeric_value(rows, field):
    values = []
    for row in rows:
        value = str(row.get(field, "")).strip()
        if value.isdigit():
            values.append(int(value))
    return (max(values) + 1) if values else 1


def pk_width(rows, field):
    widths = [
        len(str(row.get(field, "")).strip())
        for row in rows
        if str(row.get(field, "")).strip().isdigit()
    ]
    return max(widths) if widths else 3


def vocabulary_row_for_post(post, pk_value, priority):
    wp_slug = post_slug(post)
    wp_status = normalize_status(post.get("status"))
    return {
        "pk_vocabulary": pk_value,
        "base_slug": wp_slug,
        "public_title": clean_title(post),
        "vocabulary_variant": "",
        "status": wp_status,
        "cpt": "vocabulary",
        "level_tax": "",
        "grammar_tax": "",
        "topic_tax": "",
        "route_tax": "",
        "route_block": "",
        "route_step": "",
        "post_tags": "",
        "priority": str(priority),
        "output_folder": VOCABULARY_OUTPUT_FOLDER,
        "wp_post_id": str(post.get("id") or ""),
        "wp_slug": wp_slug,
        "wp_status": wp_status,
        "last_wp_seen_modified_gmt": str(post.get("modified_gmt") or post.get("modified") or ""),
    }


def sync_vocabulary_inventory(env, roadmap, dry_run):
    fieldnames, rows = read_roadmap_rows(roadmap)
    if not fieldnames:
        raise SystemExit("Missing vocabulary roadmap header")

    wp_posts = load_wp_posts(env, "vocabulary")
    updated_fieldnames = add_columns(fieldnames)

    rows_by_wp_id = {
        str(row.get("wp_post_id") or "").strip(): row
        for row in rows
        if str(row.get("wp_post_id") or "").strip()
    }
    rows_by_wp_slug = {
        str(row.get("wp_slug") or "").strip(): row
        for row in rows
        if str(row.get("wp_slug") or "").strip()
    }

    next_pk = next_numeric_value(rows, "pk_vocabulary")
    width = pk_width(rows, "pk_vocabulary")
    next_priority = next_numeric_value(rows, "priority")
    updated_rows = 0
    added_rows = 0

    emit("Vocabulary roadmap import from WordPress")
    emit("========================================")
    emit("dry_run: {}".format("yes" if dry_run else "no"))
    emit()

    for post in wp_posts:
        wp_post_id = str(post.get("id") or "").strip()
        wp_slug = post_slug(post)
        row = rows_by_wp_id.get(wp_post_id) or rows_by_wp_slug.get(wp_slug)
        wp_status = normalize_status(post.get("status"))

        if row:
            updates = {
                "public_title": clean_title(post),
                "status": wp_status,
                "wp_post_id": wp_post_id,
                "wp_slug": wp_slug,
                "wp_status": wp_status,
                "last_wp_seen_modified_gmt": str(post.get("modified_gmt") or post.get("modified") or ""),
            }
            row_changes = update_row(row, updates)
            if row_changes:
                updated_rows += 1
                emit(f"updated wp_post_id={wp_post_id} wp_slug={wp_slug}")
                for item in row_changes:
                    emit(f"  {item}")
            rows_by_wp_id[wp_post_id] = row
            rows_by_wp_slug[wp_slug] = row
            continue

        new_row = vocabulary_row_for_post(post, str(next_pk).zfill(width), next_priority)
        rows.append(new_row)
        rows_by_wp_id[wp_post_id] = new_row
        added_rows += 1
        emit(f"added wp_post_id={wp_post_id} wp_slug={wp_slug} title={new_row['public_title']}")
        next_pk += 1
        next_priority += 1

    if not dry_run:
        write_roadmap_rows(updated_fieldnames, rows, roadmap)

    emit()
    emit(f"wp_posts: {len(wp_posts)}")
    emit(f"updated_rows: {updated_rows}")
    emit(f"added_rows: {added_rows}")
    emit(f"roadmap path: {roadmap}")


def sync_grammar_metadata(env, roadmap, dry_run):
    fieldnames, rows = read_roadmap_rows(roadmap)
    if not fieldnames:
        raise SystemExit("Missing grammar roadmap header")

    wp_posts = load_wp_posts(env, "grammar")
    posts_by_slug = {str(post.get("slug") or "").strip(): post for post in wp_posts if post.get("slug")}
    posts_by_id = {str(post.get("id") or "").strip(): post for post in wp_posts if post.get("id")}
    roadmap_slugs = {row.get("base_slug", "").strip() for row in rows if row.get("base_slug", "").strip()}

    changed = 0
    missing_wp = 0
    updated_fieldnames = add_columns(fieldnames)
    route_tax_cache = {}

    emit("Roadmap metadata sync from WordPress")
    emit("====================================")
    emit("dry_run: {}".format("yes" if dry_run else "no"))
    emit()

    for row in rows:
        if row.get("cpt", "").strip() != "grammar":
            continue
        slug = row.get("base_slug", "").strip()
        post = posts_by_id.get(row.get("wp_post_id", "").strip()) or posts_by_slug.get(slug)
        if not post:
            emit(f"missing_wp slug={slug}")
            missing_wp += 1
            continue

        wp_slug = str(post.get("slug") or "").strip()
        wp_status = normalize_status(post.get("status"))
        updates = {
            "base_slug": wp_slug,
            "public_title": clean_title(post),
            "status": wp_status,
            "route_tax": route_tax_slugs(env, post, route_tax_cache),
            "route_block": post_meta_value(post, "route_block"),
            "route_step": post_meta_value(post, "route_step"),
            "wp_post_id": str(post.get("id") or ""),
            "wp_slug": wp_slug,
            "wp_status": wp_status,
            "last_wp_seen_modified_gmt": str(post.get("modified_gmt") or post.get("modified") or ""),
        }

        row_changes = update_row(row, updates)
        if row_changes:
            changed += 1
            emit(f"updated slug={slug} wp_post_id={post.get('id')}")
            for item in row_changes:
                emit(f"  {item}")

    for post in wp_posts:
        slug = str(post.get("slug") or "").strip()
        if slug and slug not in roadmap_slugs:
            emit(f"missing_repo slug={slug} wp_post_id={post.get('id')} title={clean_title(post)}")

    if not dry_run:
        write_roadmap_rows(updated_fieldnames, rows, roadmap)

    emit()
    emit(f"wp_posts: {len(wp_posts)}")
    emit(f"updated_rows: {changed}")
    emit(f"missing_wp: {missing_wp}")
    emit(f"roadmap path: {roadmap}")


def main():
    parser = argparse.ArgumentParser(description="Sync roadmap metadata from WordPress.")
    parser.add_argument("--type", default="grammar", choices=["grammar", "vocabulary"], help="Content type to sync.")
    parser.add_argument("--dry-run", action="store_true", help="Report changes without writing the roadmap.")
    args = parser.parse_args()

    env = load_env()
    roadmap = ROADMAPS[args.type]
    if args.type == "vocabulary":
        sync_vocabulary_inventory(env, roadmap, args.dry_run)
        return

    sync_grammar_metadata(env, roadmap, args.dry_run)


if __name__ == "__main__":
    main()
