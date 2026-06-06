import json

from .grammar import render_html as render_grammar_html
from .grammar import render_markdown as render_grammar_markdown
from .paths import CONTENT_DATA, GENERATED_HTML, GENERATED_MARKDOWN
from .roadmap import find_roadmap_row
from .validation import validate_grammar_data


def load_content_data(content_type, slug):
    data_path = CONTENT_DATA / content_type / f"{slug}.json"
    if not data_path.exists():
        raise SystemExit(f"Missing content-data JSON: {data_path}")
    with data_path.open(encoding="utf-8") as handle:
        return json.load(handle)


def render_content(content_type, slug):
    row = find_roadmap_row(content_type, slug)

    if content_type != "grammar":
        raise SystemExit(f"Unsupported content type: {content_type}")
    if row.get("cpt", "").strip() != "grammar":
        raise SystemExit(f"Roadmap row cpt must be grammar for {slug}")
    if row.get("lesson_type", "").strip() != "verb-usage":
        raise SystemExit(f"Unsupported grammar lesson_type for {slug}: {row.get('lesson_type', '').strip()}")

    data = load_content_data(content_type, slug)
    validate_grammar_data(data)

    markdown_dir = GENERATED_MARKDOWN / content_type
    html_dir = GENERATED_HTML / content_type
    markdown_dir.mkdir(parents=True, exist_ok=True)
    html_dir.mkdir(parents=True, exist_ok=True)

    markdown_path = markdown_dir / f"{slug}.md"
    html_path = html_dir / f"{slug}.html"

    markdown_path.write_text(render_grammar_markdown(row, data), encoding="utf-8")
    html_path.write_text(render_grammar_html(row, data), encoding="utf-8")

    return markdown_path, html_path
