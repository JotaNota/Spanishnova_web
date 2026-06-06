import urllib.parse

from .config import HTML_ROOT, ROOT
from .roadmap import (
    ensure_can_upload,
    load_roadmap,
    read_roadmap_rows,
    update_roadmap_status,
    write_roadmap_rows,
)
from .wordpress import get_term_id, wp_request


def find_html(row):
    cpt = row.get("cpt", "").strip()
    slug = row.get("base_slug", "").strip()
    return HTML_ROOT / cpt / f"{slug}.html"


def split_terms(value):
    if not value:
        return []
    return [item.strip() for item in value.replace(";", ",").split(",") if item.strip()]


def taxonomy_payload(env, row):
    payload = {}

    level_terms = split_terms(row.get("level_tax", ""))
    grammar_terms = split_terms(row.get("grammar_tax", ""))
    topic_terms = split_terms(row.get("topic_tax", ""))
    tag_terms = split_terms(row.get("post_tags", ""))

    if level_terms:
        payload["level_tax"] = [get_term_id(env, "level_tax", name) for name in level_terms]
    if grammar_terms:
        payload["grammar_tax"] = [get_term_id(env, "grammar_tax", name) for name in grammar_terms]
    if topic_terms:
        payload["topic_tax"] = [get_term_id(env, "topic_tax", name) for name in topic_terms]
    if tag_terms:
        payload["tags"] = [get_term_id(env, "tags", name, create=True) for name in tag_terms]

    return payload


def upload_one(env, row):
    ensure_can_upload(row)

    slug = row.get("base_slug", "").strip()
    html_path = find_html(row)
    if not html_path.exists():
        raise SystemExit(f"Missing HTML file: {html_path}")

    cpt = row.get("cpt", "").strip()
    title = row.get("public_title", "").strip()
    content = html_path.read_text(encoding="utf-8").strip()

    existing = wp_request(env, f"/wp-json/wp/v2/{cpt}?{urllib.parse.urlencode({'search': title, 'status': 'any', 'per_page': 100})}")
    existing = [
        post for post in existing
        if post.get("title", {}).get("rendered", "").strip() == title
    ]
    payload = {
        "title": title,
        "slug": slug,
        "status": "draft",
        "content": content,
    }
    payload.update(taxonomy_payload(env, row))

    if existing:
        post_id = existing[0]["id"]
        result = wp_request(env, f"/wp-json/wp/v2/{cpt}/{post_id}", method="POST", data=payload)
        print(f"Updated draft: {result.get('id')} {result.get('link')}")
    else:
        result = wp_request(env, f"/wp-json/wp/v2/{cpt}", method="POST", data=payload)
        print(f"Created draft: {result.get('id')} {result.get('link')}")

    update_roadmap_status(slug, "draft")
    print(f"Updated roadmap status: {slug} -> draft")


def roadmap_status_for(env, row):
    html_path = find_html(row)
    if not html_path.exists():
        return "not-created"

    cpt = row.get("cpt", "").strip()
    slug = row.get("base_slug", "").strip()
    existing = wp_request(env, f"/wp-json/wp/v2/{cpt}?{urllib.parse.urlencode({'slug': slug, 'status': 'any'})}")

    if not existing:
        return "planned"

    wp_status = existing[0].get("status", "")
    if wp_status == "publish":
        return "published"
    if wp_status == "draft":
        return "draft"
    return wp_status or "planned"


def sync_statuses(env):
    fieldnames, rows = read_roadmap_rows()

    if not fieldnames or "status" not in fieldnames:
        raise SystemExit("Missing status column in roadmap")

    changed = 0
    for row in rows:
        old_status = row.get("status", "").strip()
        new_status = roadmap_status_for(env, row)
        if old_status != new_status:
            print(f"{row.get('base_slug')}: {old_status} -> {new_status}")
            row["status"] = new_status
            changed += 1

    write_roadmap_rows(fieldnames, rows)
    print(f"Updated statuses: {changed}")


def dry_run(slug=None):
    rows = load_roadmap()
    selected = rows.values()

    if slug:
        if slug not in rows:
            raise SystemExit(f"Slug not found in roadmap: {slug}")
        selected = [rows[slug]]

    for row in selected:
        html_path = find_html(row)
        if not html_path.exists():
            continue

        content = html_path.read_text(encoding="utf-8").strip()
        post = {
            "slug": row.get("base_slug", "").strip(),
            "title": row.get("public_title", "").strip(),
            "cpt": row.get("cpt", "").strip(),
            "status": row.get("status", "").strip(),
            "level_tax": row.get("level_tax", "").strip(),
            "grammar_tax": row.get("grammar_tax", "").strip(),
            "topic_tax": row.get("topic_tax", "").strip(),
            "post_tags": row.get("post_tags", "").strip(),
            "html_path": str(html_path.relative_to(ROOT)),
            "content_chars": len(content),
        }

        print("=" * 80)
        for key, value in post.items():
            print(f"{key}: {value}")
