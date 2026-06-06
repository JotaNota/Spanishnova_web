import csv

from .config import ROADMAP


BLOCKED_UPLOAD_STATUSES = {"draft", "published", "not-created"}


def load_roadmap():
    rows = {}
    with ROADMAP.open(encoding="utf-8", newline="") as f:
        for row in csv.DictReader(f):
            slug = row.get("base_slug", "").strip()
            if slug:
                rows[slug] = row
    return rows


def read_roadmap_rows():
    with ROADMAP.open(encoding="utf-8-sig", newline="") as f:
        reader = csv.DictReader(f)
        return reader.fieldnames, list(reader)


def write_roadmap_rows(fieldnames, rows):
    with ROADMAP.open("w", encoding="utf-8", newline="") as f:
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()
        writer.writerows(rows)


def find_row_by_slug(slug):
    rows = load_roadmap()
    if slug not in rows:
        raise SystemExit(f"Slug not found in roadmap: {slug}")
    return rows[slug]


def ensure_can_upload(row):
    status = row.get("status", "").strip()
    slug = row.get("base_slug", "").strip()

    if status != "planned":
        raise SystemExit(f"Cannot upload {slug}: status is {status}. Only planned posts can be uploaded.")


def update_roadmap_status(slug, new_status):
    fieldnames, rows = read_roadmap_rows()

    if not fieldnames or "status" not in fieldnames:
        raise SystemExit("Missing status column in roadmap")

    updated = False
    for row in rows:
        if row.get("base_slug", "").strip() == slug:
            row["status"] = new_status
            updated = True
            break

    if not updated:
        raise SystemExit(f"Could not update roadmap status for: {slug}")

    write_roadmap_rows(fieldnames, rows)
