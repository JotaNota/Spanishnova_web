#!/usr/bin/env python3
import argparse
import difflib
import json

from spanishnova_upload.config import HTML_ROOT, ROOT


DATA_ROOT = ROOT / "docs/content-system/content-data"
SNAPSHOT_ROOT = ROOT / ".sync/wp-snapshots"
RECONCILE_ROOT = ROOT / ".sync/reconcile"


def read_text(path):
    if not path.exists():
        return None
    return path.read_text(encoding="utf-8")


def normalize(value):
    return str(value or "").replace("\r\n", "\n").strip()


def unified_diff(old_label, old_text, new_label, new_text):
    return "\n".join(
        difflib.unified_diff(
            normalize(old_text).splitlines(),
            normalize(new_text).splitlines(),
            fromfile=old_label,
            tofile=new_label,
            lineterm="",
        )
    )


def load_json(path):
    if not path.exists():
        return None
    return json.loads(path.read_text(encoding="utf-8-sig"))


def wp_content(snapshot):
    content = snapshot.get("content") or {}
    return content.get("raw") or content.get("rendered") or ""


def metadata_lines(snapshot):
    title = (snapshot.get("title") or {}).get("rendered", "").strip()
    return [
        f"wp_post_id: {snapshot.get('id', '')}",
        f"slug: {snapshot.get('slug', '')}",
        f"title: {title}",
        f"status: {snapshot.get('status', '')}",
        f"modified_gmt: {snapshot.get('modified_gmt', '')}",
    ]


def write_review(slug, cpt, summary, wp_html, generated_html, current_json):
    target_dir = RECONCILE_ROOT / cpt
    target_dir.mkdir(parents=True, exist_ok=True)
    review_path = target_dir / f"{slug}.md"
    current_json_text = json.dumps(current_json, ensure_ascii=False, indent=2, sort_keys=True) if current_json is not None else ""
    body = [
        f"# Reconcile WordPress snapshot: {slug}",
        "",
        "## Summary",
        "",
        summary or "No textual diff was produced.",
        "",
        "## Instructions for Codex",
        "",
        "Update the JSON content data so it represents the WordPress snapshot below. Do not edit generated Markdown/HTML directly. After JSON is updated, run the renderer to regenerate Markdown and HTML. Do not upload to WordPress from this review file.",
        "",
        "## WordPress Snapshot HTML",
        "",
        "```html",
        wp_html or "",
        "```",
        "",
        "## Generated HTML Current",
        "",
        "```html",
        generated_html or "",
        "```",
        "",
        "## JSON Current",
        "",
        "```json",
        current_json_text,
        "```",
        "",
    ]
    review_path.write_text("\n".join(body), encoding="utf-8")
    return review_path


def main():
    parser = argparse.ArgumentParser(description="Prepare repo reconciliation from a WordPress snapshot.")
    parser.add_argument("slug", help="Grammar slug to reconcile.")
    parser.add_argument("--type", default="grammar", choices=["grammar"], help="Content type to reconcile.")
    args = parser.parse_args()

    snapshot_html_path = SNAPSHOT_ROOT / args.type / f"{args.slug}.html"
    snapshot_json_path = SNAPSHOT_ROOT / args.type / f"{args.slug}.json"
    current_json_path = DATA_ROOT / args.type / f"{args.slug}.json"
    generated_html_path = HTML_ROOT / args.type / f"{args.slug}.html"

    if not snapshot_html_path.exists():
        raise SystemExit(f"Missing snapshot HTML: {snapshot_html_path}")
    if not snapshot_json_path.exists():
        raise SystemExit(f"Missing snapshot JSON: {snapshot_json_path}")

    snapshot_html = read_text(snapshot_html_path)
    snapshot_json = load_json(snapshot_json_path)
    current_json = load_json(current_json_path)
    generated_html = read_text(generated_html_path)
    snapshot_html_from_json = wp_content(snapshot_json)
    if snapshot_html_from_json and normalize(snapshot_html_from_json) != normalize(snapshot_html):
        print("metadata: snapshot JSON content differs from snapshot HTML file")

    print("Reconcile WordPress snapshot to repo")
    print("====================================")
    print(f"slug: {args.slug}")
    print(f"snapshot_html: {snapshot_html_path.relative_to(ROOT)}")
    print(f"snapshot_json: {snapshot_json_path.relative_to(ROOT)}")
    print(f"current_json: {current_json_path.relative_to(ROOT)} exists={current_json_path.exists()}")
    print(f"generated_html: {generated_html_path.relative_to(ROOT)} exists={generated_html_path.exists()}")
    print()
    print("Metadata")
    print("--------")
    for line in metadata_lines(snapshot_json):
        print(line)

    diff = unified_diff(
        "generated-html-current",
        generated_html or "",
        "wp-snapshot-html",
        snapshot_html or "",
    )

    print()
    print("Content")
    print("-------")
    if diff:
        print("content_changed: yes")
        review_path = write_review(args.slug, args.type, diff, snapshot_html, generated_html, current_json)
        print(f"review_file: {review_path.relative_to(ROOT)}")
    else:
        print("content_changed: no")
        print("review_file: not needed")


if __name__ == "__main__":
    main()
