#!/usr/bin/env python3
import argparse
import urllib.parse

from spanishnova_upload.config import ROADMAP, load_env
from spanishnova_upload.roadmap import read_roadmap_rows, write_roadmap_rows
from spanishnova_upload.wordpress import wp_request


METADATA_COLUMNS = ["wp_post_id", "wp_slug", "wp_status", "last_wp_seen_modified_gmt"]


def normalize_status(status):
    if status == "publish":
        return "published"
    return status or "planned"


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


def add_columns(fieldnames):
    updated = list(fieldnames)
    for column in METADATA_COLUMNS:
        if column not in updated:
            updated.append(column)
    return updated


def main():
    parser = argparse.ArgumentParser(description="Sync simple grammar roadmap metadata from WordPress.")
    parser.add_argument("--type", default="grammar", choices=["grammar"], help="Content type to sync.")
    parser.add_argument("--dry-run", action="store_true", help="Report changes without writing the roadmap.")
    args = parser.parse_args()

    env = load_env()
    fieldnames, rows = read_roadmap_rows()
    if not fieldnames:
        raise SystemExit("Missing grammar roadmap header")

    wp_posts = load_wp_posts(env, args.type)
    posts_by_slug = {str(post.get("slug") or "").strip(): post for post in wp_posts if post.get("slug")}
    posts_by_id = {str(post.get("id") or "").strip(): post for post in wp_posts if post.get("id")}
    roadmap_slugs = {row.get("base_slug", "").strip() for row in rows if row.get("base_slug", "").strip()}

    changed = 0
    missing_wp = 0
    updated_fieldnames = add_columns(fieldnames)

    print("Roadmap metadata sync from WordPress")
    print("====================================")
    print("dry_run: {}".format("yes" if args.dry_run else "no"))
    print()

    for row in rows:
        if row.get("cpt", "").strip() != args.type:
            continue
        slug = row.get("base_slug", "").strip()
        post = posts_by_id.get(row.get("wp_post_id", "").strip()) or posts_by_slug.get(slug)
        if not post:
            print(f"missing_wp slug={slug}")
            missing_wp += 1
            continue

        wp_slug = str(post.get("slug") or "").strip()
        wp_title = post.get("title", {}).get("rendered", "").strip()
        wp_status = normalize_status(post.get("status"))
        updates = {
            "base_slug": wp_slug,
            "public_title": wp_title,
            "status": wp_status,
            "wp_post_id": str(post.get("id") or ""),
            "wp_slug": wp_slug,
            "wp_status": wp_status,
            "last_wp_seen_modified_gmt": str(post.get("modified_gmt") or post.get("modified") or ""),
        }

        row_changes = []
        for key, value in updates.items():
            if value and row.get(key, "").strip() != value:
                row_changes.append(f"{key}: {row.get(key, '').strip()} -> {value}")
                row[key] = value

        if row_changes:
            changed += 1
            print(f"updated slug={slug} wp_post_id={post.get('id')}")
            for item in row_changes:
                print(f"  {item}")

    for post in wp_posts:
        slug = str(post.get("slug") or "").strip()
        if slug and slug not in roadmap_slugs:
            print(f"missing_repo slug={slug} wp_post_id={post.get('id')} title={post.get('title', {}).get('rendered', '').strip()}")

    if not args.dry_run:
        write_roadmap_rows(updated_fieldnames, rows)

    print()
    print(f"changed_rows: {changed}")
    print(f"missing_wp: {missing_wp}")
    print(f"roadmap: {ROADMAP}")


if __name__ == "__main__":
    main()
