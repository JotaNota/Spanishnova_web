#!/usr/bin/env python3
import argparse

from spanishnova_upload.config import load_env
from spanishnova_upload.roadmap import find_row_by_slug
from spanishnova_upload.uploader import audit_grammar, dry_run, grammar_order, sync_statuses, upload_one
from spanishnova_upload.wordpress import wp_request


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
        upload_one(env, find_row_by_slug(args.slug))
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
