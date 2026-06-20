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


def get_route_term_id(env, name_or_slug):
    query = urllib.parse.urlencode({"slug": name_or_slug})
    existing = wp_request(env, f"/wp-json/wp/v2/route_tax?{query}")

    if existing:
        return existing[0]["id"]

    return get_term_id(env, "route_tax", name_or_slug)


def taxonomy_payload(env, row):
    payload = {}

    level_terms = split_terms(row.get("level_tax", ""))
    grammar_terms = split_terms(row.get("grammar_tax", ""))
    topic_terms = split_terms(row.get("topic_tax", ""))
    route_terms = split_terms(row.get("route_tax", ""))
    tag_terms = split_terms(row.get("post_tags", ""))

    if level_terms:
        payload["level_tax"] = [get_term_id(env, "level_tax", name) for name in level_terms]
    if grammar_terms:
        payload["grammar_tax"] = [get_term_id(env, "grammar_tax", name) for name in grammar_terms]
    if topic_terms:
        payload["topic_tax"] = [get_term_id(env, "topic_tax", name) for name in topic_terms]
    if route_terms:
        payload["route_tax"] = [get_route_term_id(env, name) for name in route_terms]
    if tag_terms:
        payload["tags"] = [get_term_id(env, "tags", name, create=True) for name in tag_terms]

    return payload


def route_meta_payload(row):
    meta = {}
    route_block = str(row.get("route_block", "") or "").strip()
    route_step = str(row.get("route_step", "") or "").strip()

    if route_block:
        meta["route_block"] = route_block
    if route_step:
        try:
            meta["route_step"] = int(route_step)
        except ValueError:
            raise SystemExit(f"Invalid route_step for {row.get('base_slug', '').strip()}: {route_step}")

    return {"meta": meta} if meta else {}


def route_payload(env, row):
    payload = {}
    payload.update(taxonomy_payload(env, row))
    payload.update(route_meta_payload(row))
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
    payload.update(route_payload(env, row))

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
    cpt = row.get("cpt", "").strip()
    slug = row.get("base_slug", "").strip()
    query = urllib.parse.urlencode({"slug": slug, "status": "any", "per_page": 100})
    existing = wp_request(env, f"/wp-json/wp/v2/{cpt}?{query}")

    if existing:
        wp_status = existing[0].get("status", "")
        if wp_status == "publish":
            return "published"
        if wp_status == "draft":
            return "draft"
        return wp_status or "planned"

    html_path = find_html(row)
    if html_path.exists():
        return "planned"
    return "not-created"


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


def print_slug_section(title, slugs):
    print()
    print(title)
    print("-" * len(title))
    if not slugs:
        print("None")
        return
    for slug in sorted(slugs):
        print(slug)


def audit_grammar(env):
    fieldnames, rows = read_roadmap_rows()
    if not fieldnames:
        raise SystemExit("Missing grammar roadmap header")

    roadmap_slugs = set()
    duplicate_roadmap_slugs = set()
    suspicious = []

    for row in rows:
        slug = row.get("base_slug", "").strip()
        cpt = row.get("cpt", "").strip()

        if not slug:
            suspicious.append("Roadmap row with empty base_slug")
            continue
        if slug in roadmap_slugs:
            duplicate_roadmap_slugs.add(slug)
        roadmap_slugs.add(slug)

        if cpt and cpt != "grammar":
            suspicious.append(f"Roadmap slug {slug} has cpt={cpt}")

    html_dir = HTML_ROOT / "grammar"
    html_slugs = set()
    duplicate_html_slugs = set()
    if html_dir.exists():
        for path in html_dir.glob("*.html"):
            slug = path.stem.strip()
            if not slug:
                suspicious.append(f"Generated HTML with empty slug: {path.name}")
                continue
            if slug in html_slugs:
                duplicate_html_slugs.add(slug)
            html_slugs.add(slug)
    else:
        suspicious.append(f"Generated HTML folder missing: {html_dir.relative_to(ROOT)}")

    query = urllib.parse.urlencode({"status": "any", "per_page": 100})
    wp_posts = wp_request(env, f"/wp-json/wp/v2/grammar?{query}")
    wp_slugs = set()
    duplicate_wp_slugs = set()
    for post in wp_posts:
        slug = str(post.get("slug", "")).strip()
        if not slug:
            suspicious.append(f"WordPress grammar post without slug: id={post.get('id')}")
            continue
        if slug in wp_slugs:
            duplicate_wp_slugs.add(slug)
        wp_slugs.add(slug)

    print("Grammar audit")
    print("=============")
    print(f"Roadmap rows: {len(rows)}")
    print(f"Roadmap slugs: {len(roadmap_slugs)}")
    print(f"Generated HTML files: {len(html_slugs)}")
    print(f"WordPress grammar posts checked: {len(wp_posts)}")

    print_slug_section("1. In roadmap but not in WordPress", roadmap_slugs - wp_slugs)
    print_slug_section("2. In WordPress but not in roadmap", wp_slugs - roadmap_slugs)
    print_slug_section("3. In generated HTML but not in roadmap", html_slugs - roadmap_slugs)
    print_slug_section("4. In roadmap without generated HTML but present in WordPress", (roadmap_slugs - html_slugs) & wp_slugs)

    duplicate_or_suspicious = []
    duplicate_or_suspicious.extend(f"Duplicate roadmap slug: {slug}" for slug in sorted(duplicate_roadmap_slugs))
    duplicate_or_suspicious.extend(f"Duplicate generated HTML slug: {slug}" for slug in sorted(duplicate_html_slugs))
    duplicate_or_suspicious.extend(f"Duplicate WordPress slug: {slug}" for slug in sorted(duplicate_wp_slugs))
    duplicate_or_suspicious.extend(suspicious)
    print_slug_section("5. Duplicates or suspicious slugs", duplicate_or_suspicious)


def parse_priority(value):
    value = str(value or "").strip()
    if not value:
        return None
    try:
        return int(value)
    except ValueError:
        return None


def find_grammar_post_by_slug(env, slug):
    query = urllib.parse.urlencode({"slug": slug, "status": "any", "per_page": 1})
    posts = wp_request(env, f"/wp-json/wp/v2/grammar?{query}")
    if not posts:
        return None
    return posts[0]


def grammar_order(sync=False, env=None):
    if env is None:
        raise SystemExit("Missing WordPress environment")

    fieldnames, rows = read_roadmap_rows()
    if not fieldnames:
        raise SystemExit("Missing grammar roadmap header")

    changed = 0
    unchanged = 0
    missing = 0
    invalid = 0

    priorities = [
        priority
        for row in rows
        if row.get("cpt", "").strip() == "grammar"
        and row.get("status", "").strip() != "not-created"
        for priority in [parse_priority(row.get("priority", ""))]
        if priority is not None
    ]
    if not priorities:
        raise SystemExit("No numeric grammar priorities found")
    max_priority = max(priorities)

    print("Grammar order sync" if sync else "Grammar order dry run")
    print("==================" if sync else "=====================")

    for row in rows:
        cpt = row.get("cpt", "").strip()
        if cpt != "grammar":
            continue
        if row.get("status", "").strip() == "not-created":
            continue

        slug = row.get("base_slug", "").strip()
        priority = parse_priority(row.get("priority", ""))
        if not slug:
            print("invalid slug=(empty) priority={}".format(row.get("priority", "").strip()))
            invalid += 1
            continue
        if priority is None:
            print(f"invalid slug={slug} priority={row.get('priority', '').strip()}")
            invalid += 1
            continue

        target_menu_order = max_priority + 1 - priority
        post = find_grammar_post_by_slug(env, slug)
        if post is None:
            print(f"missing slug={slug} priority={priority} target_menu_order={target_menu_order}")
            missing += 1
            continue

        current_menu_order = int(post.get("menu_order") or 0)
        if current_menu_order == target_menu_order:
            unchanged += 1
            continue

        print(f"slug={slug} priority={priority} current_menu_order={current_menu_order} target_menu_order={target_menu_order}")
        changed += 1

        if sync:
            wp_request(
                env,
                f"/wp-json/wp/v2/grammar/{post.get('id')}",
                method="POST",
                data={"menu_order": target_menu_order},
            )

    print()
    print(f"changed: {changed}")
    print(f"unchanged: {unchanged}")
    print(f"missing: {missing}")
    print(f"invalid: {invalid}")


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
            "route_tax": row.get("route_tax", "").strip(),
            "route_block": row.get("route_block", "").strip(),
            "route_step": row.get("route_step", "").strip(),
            "post_tags": row.get("post_tags", "").strip(),
            "html_path": str(html_path.relative_to(ROOT)),
            "content_chars": len(content),
        }

        print("=" * 80)
        for key, value in post.items():
            print(f"{key}: {value}")
