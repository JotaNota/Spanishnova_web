import csv

from .paths import ROADMAPS


def find_roadmap_row(content_type, slug):
    roadmap_path = ROADMAPS.get(content_type)
    if roadmap_path is None:
        raise SystemExit(f"Unsupported content type: {content_type}")
    if not roadmap_path.exists():
        raise SystemExit(f"Missing roadmap: {roadmap_path}")

    with roadmap_path.open(encoding="utf-8-sig", newline="") as handle:
        for row in csv.DictReader(handle):
            if row.get("base_slug", "").strip() == slug:
                return row

    raise SystemExit(f"Slug not found in {roadmap_path}: {slug}")
