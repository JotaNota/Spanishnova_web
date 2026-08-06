#!/usr/bin/env python3
import argparse
import html
import json
import re
import sys
import urllib.parse
from html.parser import HTMLParser

from spanishnova_upload.config import ROADMAPS, ROOT, load_env
from spanishnova_upload.roadmap import read_roadmap_rows, write_roadmap_rows
from spanishnova_upload.wordpress import wp_request


CONTENT_DATA_DIR = ROOT / "docs/content-system/content-data/vocabulary"
GENERATED_HTML_DIR = ROOT / "docs/content-system/generated/generated-html-posts/vocabulary"
GENERATED_MARKDOWN_DIR = ROOT / "docs/content-system/generated/generated-markdown-posts/vocabulary"
ROADMAP_PATH = ROADMAPS["vocabulary"]
VOCABULARY_OUTPUT_FOLDER = "docs/content-system/generated/generated-markdown-posts/vocabulary/"
ROADMAP_METADATA_COLUMNS = ["wp_post_id", "wp_slug", "wp_status", "last_wp_seen_modified_gmt"]


class MarkdownHTMLParser(HTMLParser):
    BLOCK_TAGS = {
        "address",
        "article",
        "aside",
        "blockquote",
        "div",
        "footer",
        "form",
        "header",
        "main",
        "nav",
        "p",
        "pre",
        "section",
    }

    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.lines = []
        self.current = []
        self.list_stack = []
        self.link_href = None

    def flush(self):
        text = "".join(self.current)
        text = re.sub(r"[ \t\r\f\v]+", " ", text).strip()
        self.current = []
        if text:
            self.lines.append(text)

    def blank(self):
        if self.lines and self.lines[-1] != "":
            self.lines.append("")

    def append(self, text):
        self.current.append(text)

    def handle_starttag(self, tag, attrs):
        attrs = dict(attrs)
        if tag in self.BLOCK_TAGS:
            self.flush()
            self.blank()
        elif tag in {"br", "hr"}:
            self.flush()
            self.blank()
        elif tag in {"h1", "h2", "h3", "h4", "h5", "h6"}:
            self.flush()
            self.blank()
            self.append("#" * int(tag[1]) + " ")
        elif tag in {"strong", "b"}:
            self.append("**")
        elif tag in {"em", "i"}:
            self.append("*")
        elif tag in {"ul", "ol"}:
            self.flush()
            self.blank()
            self.list_stack.append({"tag": tag, "index": 0})
        elif tag == "li":
            self.flush()
            if self.list_stack and self.list_stack[-1]["tag"] == "ol":
                self.list_stack[-1]["index"] += 1
                marker = f"{self.list_stack[-1]['index']}. "
            else:
                marker = "- "
            self.append(marker)
        elif tag == "a":
            self.link_href = attrs.get("href")

    def handle_endtag(self, tag):
        if tag in self.BLOCK_TAGS or tag in {"li", "h1", "h2", "h3", "h4", "h5", "h6"}:
            self.flush()
            self.blank()
        elif tag in {"strong", "b"}:
            self.append("**")
        elif tag in {"em", "i"}:
            self.append("*")
        elif tag in {"ul", "ol"}:
            self.flush()
            self.blank()
            if self.list_stack:
                self.list_stack.pop()
        elif tag == "a":
            if self.link_href:
                self.append(f" ({self.link_href})")
            self.link_href = None

    def handle_data(self, data):
        self.append(data)

    def markdown(self):
        self.flush()
        while self.lines and self.lines[-1] == "":
            self.lines.pop()
        compact = []
        previous_blank = False
        for line in self.lines:
            if line == "":
                if not previous_blank:
                    compact.append(line)
                previous_blank = True
            else:
                compact.append(line)
                previous_blank = False
        return "\n".join(compact).rstrip() + "\n"


def emit(message=""):
    encoding = sys.stdout.encoding or "utf-8"
    safe_message = str(message).encode(encoding, errors="replace").decode(encoding)
    print(safe_message)


def ensure_expected_paths():
    required = [CONTENT_DATA_DIR, GENERATED_HTML_DIR, GENERATED_MARKDOWN_DIR, ROADMAP_PATH]
    missing = [str(path) for path in required if not path.exists()]
    if missing:
        raise SystemExit("Missing required vocabulary paths:\n" + "\n".join(missing))


def post_slug(post):
    return str(post.get("slug") or post.get("generated_slug") or "").strip()


def clean_title(post):
    title = post.get("title") or {}
    value = title.get("raw") or title.get("rendered") or ""
    value = re.sub(r"<[^>]+>", "", value)
    return html.unescape(value).strip()


def normalize_status(status):
    if status == "publish":
        return "published"
    return status or "planned"


def post_html(post):
    content = post.get("content") or {}
    return str(content.get("raw") or content.get("rendered") or "").strip()


def normalize_html(value):
    value = html.unescape(str(value or ""))
    value = value.replace("\r\n", "\n").replace("\r", "\n")
    value = re.sub(r">\s+<", "><", value)
    value = re.sub(r"\s+", " ", value)
    return value.strip()


def html_to_markdown(title, source_html):
    parser = MarkdownHTMLParser()
    parser.feed(source_html)
    body = parser.markdown().strip()
    title = title.strip()
    if body.startswith("# "):
        return body + "\n"
    if body:
        return f"# {title}\n\n{body}\n"
    return f"# {title}\n"


def minimal_json_for_post(post, source_html):
    return {
        "title": clean_title(post),
        "source": "wordpress-local",
        "wp_post_id": post.get("id"),
        "wp_slug": post_slug(post),
        "wp_status": normalize_status(post.get("status")),
        "last_wp_seen_modified_gmt": str(post.get("modified_gmt") or post.get("modified") or ""),
        "source_html": source_html,
        "notes": "Pulled from local WordPress. Structured vocabulary fields were not reconstructed from HTML.",
    }


def load_wp_posts(env):
    posts = []
    page = 1
    while True:
        query = urllib.parse.urlencode(
            {"status": "any", "context": "edit", "per_page": 100, "page": page}
        )
        batch = wp_request(env, f"/wp-json/wp/v2/vocabulary?{query}")
        if not batch:
            break
        posts.extend(batch)
        if len(batch) < 100:
            break
        page += 1
    return posts


def add_columns(fieldnames):
    updated = list(fieldnames)
    for column in ROADMAP_METADATA_COLUMNS:
        if column not in updated:
            updated.append(column)
    return updated


def next_numeric_value(rows, field):
    values = []
    for row in rows:
        value = str(row.get(field, "")).strip()
        if value.isdigit():
            values.append(int(value))
    return (max(values) + 1) if values else 1


def pk_width(rows):
    widths = [
        len(str(row.get("pk_vocabulary", "")).strip())
        for row in rows
        if str(row.get("pk_vocabulary", "")).strip().isdigit()
    ]
    return max(widths) if widths else 3


def roadmap_indexes(rows):
    by_wp_id = {}
    by_slug = {}
    for row in rows:
        wp_post_id = str(row.get("wp_post_id") or "").strip()
        if wp_post_id:
            by_wp_id[wp_post_id] = row
        for key in ("base_slug", "wp_slug"):
            slug = str(row.get(key) or "").strip()
            if slug and slug not in by_slug:
                by_slug[slug] = row
    return by_wp_id, by_slug


def new_roadmap_row(post, pk_value, priority):
    slug = post_slug(post)
    status = normalize_status(post.get("status"))
    return {
        "pk_vocabulary": pk_value,
        "base_slug": slug,
        "public_title": clean_title(post),
        "vocabulary_variant": "",
        "status": status,
        "cpt": "vocabulary",
        "level_tax": "",
        "grammar_tax": "",
        "topic_tax": "",
        "post_tags": "",
        "priority": str(priority),
        "output_folder": VOCABULARY_OUTPUT_FOLDER,
        "wp_post_id": str(post.get("id") or ""),
        "wp_slug": slug,
        "wp_status": status,
        "last_wp_seen_modified_gmt": str(post.get("modified_gmt") or post.get("modified") or ""),
        "last_wp_upload_modified_gmt": "",
    }


def update_roadmap_row(row, post):
    updates = {
        "base_slug": post_slug(post),
        "public_title": clean_title(post),
        "status": normalize_status(post.get("status")),
        "cpt": "vocabulary",
        "wp_post_id": str(post.get("id") or ""),
        "wp_slug": post_slug(post),
        "wp_status": normalize_status(post.get("status")),
        "last_wp_seen_modified_gmt": str(post.get("modified_gmt") or post.get("modified") or ""),
    }
    changed = []
    for key, value in updates.items():
        value = str(value or "")
        if row.get(key, "").strip() != value:
            changed.append(key)
            row[key] = value
    return changed


def read_text_if_exists(path):
    if not path.exists():
        return None
    return path.read_text(encoding="utf-8")


def write_text(path, text):
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(text, encoding="utf-8")


def planned_file_actions(slug, post, source_html):
    title = clean_title(post) or slug
    return {
        CONTENT_DATA_DIR / f"{slug}.json": json.dumps(
            minimal_json_for_post(post, source_html), ensure_ascii=False, indent=2
        )
        + "\n",
        GENERATED_HTML_DIR / f"{slug}.html": source_html.rstrip() + "\n",
        GENERATED_MARKDOWN_DIR / f"{slug}.md": html_to_markdown(title, source_html),
    }


def apply_file_actions(actions):
    for path, content in actions.items():
        write_text(path, content)


def pull_vocabulary(env, only_slug, apply):
    ensure_expected_paths()
    fieldnames, rows = read_roadmap_rows(ROADMAP_PATH)
    if not fieldnames:
        raise SystemExit("Missing vocabulary roadmap header")

    posts = load_wp_posts(env)
    if only_slug:
        posts = [post for post in posts if post_slug(post) == only_slug]
        if not posts:
            raise SystemExit(f"Slug not found in local WordPress vocabulary: {only_slug}")

    updated_fieldnames = add_columns(fieldnames)
    by_wp_id, by_slug = roadmap_indexes(rows)
    next_pk = next_numeric_value(rows, "pk_vocabulary")
    width = pk_width(rows)
    next_priority = next_numeric_value(rows, "priority")
    summary = {"nuevos": 0, "iguales": 0, "actualizados": 0, "errores": 0, "roadmap_rows_creadas": 0}
    errors = []
    roadmap_changed = False

    emit("Pull local WordPress vocabulary to repo")
    emit("=======================================")
    emit(f"mode: {'apply' if apply else 'dry-run'}")
    if only_slug:
        emit(f"slug: {only_slug}")
    emit()

    for post in posts:
        slug = post_slug(post)
        wp_post_id = str(post.get("id") or "").strip()
        if not slug:
            summary["errores"] += 1
            errors.append(f"wp_post_id={wp_post_id or '?'} has no slug")
            continue

        source_html = post_html(post)
        if not source_html:
            summary["errores"] += 1
            errors.append(f"slug={slug} wp_post_id={wp_post_id or '?'} has empty content")
            continue

        repo_slug = post_slug(post)
        html_path = GENERATED_HTML_DIR / f"{repo_slug}.html"
        json_path = CONTENT_DATA_DIR / f"{repo_slug}.json"
        markdown_path = GENERATED_MARKDOWN_DIR / f"{repo_slug}.md"
        repo_html = read_text_if_exists(html_path)
        row = by_wp_id.get(wp_post_id) if wp_post_id else None
        row = row or by_slug.get(slug)
        is_new = row is None or repo_html is None or not json_path.exists() or not markdown_path.exists()

        if not is_new and normalize_html(repo_html) == normalize_html(source_html):
            summary["iguales"] += 1
            emit(f"igual slug={repo_slug}")
            continue

        if row:
            row_changes = update_roadmap_row(row, post)
            if row_changes:
                roadmap_changed = True
        else:
            row = new_roadmap_row(post, str(next_pk).zfill(width), next_priority)
            rows.append(row)
            by_wp_id[str(row.get("wp_post_id") or "").strip()] = row
            by_slug[slug] = row
            next_pk += 1
            next_priority += 1
            roadmap_changed = True
            summary["roadmap_rows_creadas"] += 1

        if apply:
            apply_file_actions(planned_file_actions(repo_slug, post, source_html))

        if is_new:
            summary["nuevos"] += 1
            emit(f"nuevo slug={repo_slug}")
        else:
            summary["actualizados"] += 1
            emit(f"actualizado slug={repo_slug}")

    if apply and roadmap_changed:
        write_roadmap_rows(updated_fieldnames, rows, ROADMAP_PATH)

    emit()
    emit("Resumen")
    emit("-------")
    for key in ["nuevos", "iguales", "actualizados", "errores", "roadmap_rows_creadas"]:
        emit(f"{key}: {summary[key]}")

    if errors:
        emit()
        emit("Errores")
        for item in errors:
            emit(f"- {item}")

    if not apply:
        emit()
        emit("Dry-run: no se escribieron archivos. Usa --apply para aplicar cambios.")


def main():
    parser = argparse.ArgumentParser(
        description="Pull local WordPress vocabulary CPT posts into repo content files."
    )
    mode = parser.add_mutually_exclusive_group()
    mode.add_argument("--dry-run", action="store_true", help="Preview changes without writing files. Default.")
    mode.add_argument("--apply", action="store_true", help="Write vocabulary JSON, HTML, Markdown, and roadmap changes.")
    parser.add_argument("--slug", help="Process one WordPress vocabulary slug.")
    args = parser.parse_args()

    env = load_env()
    pull_vocabulary(env, args.slug, args.apply)


if __name__ == "__main__":
    main()
