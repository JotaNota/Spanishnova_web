import json

from .grammar import render_html as render_grammar_html
from .grammar import render_markdown as render_grammar_markdown
from .paths import CONTENT_DATA, GENERATED_HTML, GENERATED_MARKDOWN
from .roadmap import find_roadmap_row
from .validation import validate_grammar_data
from .vocabulary import render_html as render_vocabulary_html
from .vocabulary import render_markdown as render_vocabulary_markdown
from .vocabulary import validate_vocabulary_data


def load_content_data(content_type, slug):
    data_path = CONTENT_DATA / content_type / f"{slug}.json"
    if not data_path.exists():
        raise SystemExit(f"Missing content-data JSON: {data_path}")
    with data_path.open(encoding="utf-8") as handle:
        return json.load(handle)


def render_content(content_type, slug):
    row = find_roadmap_row(content_type, slug)

    data = load_content_data(content_type, slug)
    if row.get("cpt", "").strip() != content_type:
        raise SystemExit(f"Roadmap row cpt must be {content_type} for {slug}")

    if content_type == "grammar":
        supported_grammar_lesson_types = {"tense", "verb-usage", "structure", "comparison", "particle-set"}
        if row.get("lesson_type", "").strip() not in supported_grammar_lesson_types:
            raise SystemExit(f"Unsupported grammar lesson_type for {slug}: {row.get('lesson_type', '').strip()}")
        validate_grammar_data(data, row.get("lesson_type", "").strip())
        markdown = render_grammar_markdown(row, data)
        html = render_grammar_html(row, data)
    elif content_type == "vocabulary":
        validate_vocabulary_data(data)
        markdown = render_vocabulary_markdown(row, data)
        html = render_vocabulary_html(row, data)
    else:
        raise SystemExit(f"Unsupported content type: {content_type}")

    markdown_dir = GENERATED_MARKDOWN / content_type
    html_dir = GENERATED_HTML / content_type
    markdown_dir.mkdir(parents=True, exist_ok=True)
    html_dir.mkdir(parents=True, exist_ok=True)

    markdown_path = markdown_dir / f"{slug}.md"
    html_path = html_dir / f"{slug}.html"

    markdown_path.write_text(markdown, encoding="utf-8")
    html_path.write_text(html, encoding="utf-8")

    return markdown_path, html_path
