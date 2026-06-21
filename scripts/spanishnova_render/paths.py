from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
CONTENT_SYSTEM = ROOT / "docs/content-system"
CONTENT_PLAN = CONTENT_SYSTEM / "roadmaps"
CONTENT_DATA = CONTENT_SYSTEM / "generated/json"
GENERATED_MARKDOWN = CONTENT_SYSTEM / "generated/markdown"
GENERATED_HTML = CONTENT_SYSTEM / "generated/html"

ROADMAPS = {
    "grammar": CONTENT_PLAN / "grammar-roadmap.csv",
    "vocabulary": CONTENT_PLAN / "vocabulary-roadmap.csv",
}
