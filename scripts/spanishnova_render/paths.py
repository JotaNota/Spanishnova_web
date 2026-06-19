from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
CONTENT_SYSTEM = ROOT / "docs/content-system"
CONTENT_PLAN = CONTENT_SYSTEM / "content-plan"
CONTENT_DATA = CONTENT_SYSTEM / "content-data"
GENERATED_MARKDOWN = CONTENT_SYSTEM / "generated/generated-markdown-posts"
GENERATED_HTML = CONTENT_SYSTEM / "generated/generated-html-posts"

ROADMAPS = {
    "grammar": CONTENT_PLAN / "grammar-roadmap.csv",
    "vocabulary": CONTENT_PLAN / "vocabulary-roadmap.csv",
}
