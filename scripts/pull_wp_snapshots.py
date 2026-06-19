#!/usr/bin/env python3
import argparse
import json
import urllib.parse

from spanishnova_upload.config import ROOT, load_env
from spanishnova_upload.wordpress import wp_request


SNAPSHOT_ROOT = ROOT / ".sync/wp-snapshots"


def wp_content(post):
    content = post.get("content") or {}
    return content.get("raw") or content.get("rendered") or ""


def find_post_by_slug(env, cpt, slug):
    query = urllib.parse.urlencode({"slug": slug, "status": "any", "per_page": 1, "context": "edit"})
    posts = wp_request(env, f"/wp-json/wp/v2/{cpt}?{query}")
    if not posts:
        return None
    return posts[0]


def write_snapshot(cpt, slug, post):
    target_dir = SNAPSHOT_ROOT / cpt
    target_dir.mkdir(parents=True, exist_ok=True)
    html_path = target_dir / f"{slug}.html"
    json_path = target_dir / f"{slug}.json"

    html_path.write_text(wp_content(post), encoding="utf-8")
    json_path.write_text(json.dumps(post, ensure_ascii=False, indent=2, sort_keys=True), encoding="utf-8")
    print(f"snapshot_html: {html_path.relative_to(ROOT)}")
    print(f"snapshot_json: {json_path.relative_to(ROOT)}")


def main():
    parser = argparse.ArgumentParser(description="Pull temporary WordPress snapshots into .sync/.")
    parser.add_argument("slugs", nargs="+", help="One or more WordPress slugs to snapshot.")
    parser.add_argument("--type", default="grammar", choices=["grammar"], help="Content type to snapshot.")
    args = parser.parse_args()

    env = load_env()
    missing = 0
    for slug in args.slugs:
        post = find_post_by_slug(env, args.type, slug)
        if not post:
            print(f"missing_wp slug={slug}")
            missing += 1
            continue
        print(f"pulled slug={slug} wp_post_id={post.get('id')} status={post.get('status')}")
        write_snapshot(args.type, slug, post)

    if missing:
        raise SystemExit(f"Missing WordPress posts: {missing}")


if __name__ == "__main__":
    main()
