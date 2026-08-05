#!/usr/bin/env python3
"""Preview or apply grammar roadmap fields to existing Grammar posts.

Posts are identified exclusively by the wp_post_id column. The default workflow is
--dry-run; --apply must be supplied explicitly to write to WordPress.
"""

import argparse
import csv
import html
import json
import re
import sys
import urllib.parse
import zipfile
from pathlib import Path
from xml.etree import ElementTree

from spanishnova_upload.config import ROOT, load_env
from spanishnova_upload.wordpress import wp_request

if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8")


ROADMAP_PATH = ROOT / "docs/content-system/roadmaps/grammar-roadmap.csv"
REQUIRED_COLUMNS = [
    "wp_post_id",
    "public_title",
    "grammar_section",
    "grammar_group",
    "grammar_order",
    "search_terms",
]
META_FIELDS = ("grammar_section", "grammar_group", "grammar_order", "search_terms")
MAIN_NS = "http://schemas.openxmlformats.org/spreadsheetml/2006/main"
DOC_REL_NS = "http://schemas.openxmlformats.org/officeDocument/2006/relationships"
PKG_REL_NS = "http://schemas.openxmlformats.org/package/2006/relationships"


def parse_args():
    parser = argparse.ArgumentParser(description="Synchronize grammar-roadmap fields using wp_post_id.")
    mode = parser.add_mutually_exclusive_group(required=True)
    mode.add_argument("--dry-run", action="store_true", help="Preview changes without writing to WordPress.")
    mode.add_argument("--apply", action="store_true", help="Write the previewed changes to WordPress.")
    return parser.parse_args()


def clean(value):
    return str(value if value is not None else "").strip()


def column_index(reference):
    letters = re.match(r"[A-Z]+", reference or "")
    if not letters:
        return 0
    index = 0
    for letter in letters.group(0):
        index = index * 26 + ord(letter) - ord("A") + 1
    return index - 1


def read_shared_strings(archive):
    try:
        root = ElementTree.fromstring(archive.read("xl/sharedStrings.xml"))
    except KeyError:
        return []
    return ["".join(node.text or "" for node in item.iter(f"{{{MAIN_NS}}}t")) for item in root.findall(f"{{{MAIN_NS}}}si")]


def xlsx_cell_value(cell, shared_strings):
    if cell.get("t") == "inlineStr":
        return "".join(node.text or "" for node in cell.iter(f"{{{MAIN_NS}}}t"))
    value = cell.find(f"{{{MAIN_NS}}}v")
    if value is None or value.text is None:
        return ""
    if cell.get("t") == "s":
        return shared_strings[int(value.text)]
    return value.text


def read_xlsx(path):
    with zipfile.ZipFile(path) as archive:
        workbook = ElementTree.fromstring(archive.read("xl/workbook.xml"))
        relationships = ElementTree.fromstring(archive.read("xl/_rels/workbook.xml.rels"))
        targets = {
            relationship.get("Id"): "xl/" + relationship.get("Target", "").lstrip("/")
            for relationship in relationships.findall(f"{{{PKG_REL_NS}}}Relationship")
        }
        first_sheet = workbook.find(f".//{{{MAIN_NS}}}sheet")
        if first_sheet is None:
            raise SystemExit("Roadmap workbook has no worksheet")
        sheet_path = targets.get(first_sheet.get(f"{{{DOC_REL_NS}}}id"))
        if not sheet_path:
            raise SystemExit("Cannot resolve the roadmap worksheet")
        shared_strings = read_shared_strings(archive)
        sheet = ElementTree.fromstring(archive.read(sheet_path))
        matrix = []
        for xml_row in sheet.findall(f".//{{{MAIN_NS}}}row"):
            values = {column_index(cell.get("r")): xlsx_cell_value(cell, shared_strings) for cell in xml_row.findall(f"{{{MAIN_NS}}}c")}
            if values:
                matrix.append([values.get(index, "") for index in range(max(values) + 1)])
    return matrix


def read_csv(path):
    with path.open("r", encoding="utf-8-sig", newline="") as source:
        return list(csv.reader(source))


def read_rows(path):
    if not path.exists():
        raise SystemExit(f"Missing roadmap source: {path}")
    matrix = read_xlsx(path) if zipfile.is_zipfile(path) else read_csv(path)
    if not matrix:
        raise SystemExit("Roadmap source is empty")
    headers = [clean(value) for value in matrix[0]]
    missing = [column for column in REQUIRED_COLUMNS if column not in headers]
    if missing:
        raise SystemExit("Missing required columns: " + ", ".join(missing))
    rows = []
    for row_number, values in enumerate(matrix[1:], start=2):
        padded = values + [""] * (len(headers) - len(values))
        row = {header: padded[index] for index, header in enumerate(headers)}
        if any(clean(value) for value in row.values()):
            row["_row_number"] = row_number
            rows.append(row)
    return headers, rows


def parse_post_id(value):
    value = clean(value)
    match = re.fullmatch(r"([1-9]\d*)(?:\.0+)?", value)
    return int(match.group(1)) if match else None


def parse_order(value):
    value = clean(value)
    match = re.fullmatch(r"(-?\d+)(?:\.0+)?", value)
    return int(match.group(1)) if match else None


def fetch_posts(env, post_ids):
    if not post_ids:
        return {}
    query = urllib.parse.urlencode({"include": ",".join(map(str, sorted(post_ids))), "status": "any", "context": "edit", "per_page": 100})
    posts = wp_request(env, f"/wp-json/wp/v2/grammar?{query}")
    return {int(post["id"]): post for post in posts}


def current_title(post):
    return html.unescape(re.sub(r"<[^>]+>", "", post.get("title", {}).get("rendered", ""))).strip()


def proposed_values(row):
    return {
        "title": clean(row["public_title"]),
        "grammar_section": clean(row["grammar_section"]),
        "grammar_group": clean(row["grammar_group"]),
        "grammar_order": parse_order(row["grammar_order"]),
        "search_terms": clean(row["search_terms"]),
    }


def format_value(value):
    return json.dumps(value, ensure_ascii=False)


def main():
    args = parse_args()
    headers, rows = read_rows(ROADMAP_PATH)
    print("Source:", ROADMAP_PATH)
    print("Format:", "XLSX container" if zipfile.is_zipfile(ROADMAP_PATH) else "CSV text")
    print("Headers:", ", ".join(headers))

    missing_ids = []
    invalid_rows = []
    candidates = []
    for row in rows:
        post_id = parse_post_id(row["wp_post_id"])
        required_empty = [field for field in REQUIRED_COLUMNS[1:] if clean(row[field]) == ""]
        order_is_valid = parse_order(row["grammar_order"]) is not None
        if post_id is None:
            missing_ids.append(row)
        if required_empty or not order_is_valid:
            invalid_rows.append((row, required_empty + ([] if order_is_valid else ["grammar_order must be an integer"])))
        elif post_id is not None:
            candidates.append((row, post_id))

    duplicate_ids = {post_id for _, post_id in candidates if sum(candidate_id == post_id for _, candidate_id in candidates) > 1}
    if duplicate_ids:
        remaining = []
        for row, post_id in candidates:
            if post_id in duplicate_ids:
                invalid_rows.append((row, ["wp_post_id appears more than once"]));
            else:
                remaining.append((row, post_id))
        candidates = remaining

    posts = fetch_posts(load_env(), {post_id for _, post_id in candidates})
    not_found = []
    processed = []
    for row, post_id in candidates:
        post = posts.get(post_id)
        if post is None:
            not_found.append((row, post_id))
        else:
            processed.append((row, post_id, post))

    changes_planned = 0
    print("\nPOSTS")
    for row, post_id, post in processed:
        proposed = proposed_values(row)
        meta = post.get("meta") or {}
        changes = []
        if current_title(post) != proposed["title"]:
            changes.append(("title", current_title(post), proposed["title"]))
        for field in META_FIELDS:
            current = meta.get(field, "")
            if field == "grammar_order":
                current = parse_order(current)
            else:
                current = clean(current)
            if current != proposed[field]:
                changes.append((field, current, proposed[field]))
        changes_planned += len(changes)
        print(f"\n{post_id} | {current_title(post)} → {proposed['title']}")
        if changes:
            for field, before, after in changes:
                print(f"  {field}: {format_value(before)} → {format_value(after)}")
        else:
            print("  NO CHANGE")

    print("\nMISSING wp_post_id")
    for row in missing_ids:
        print(f"  row {row['_row_number']}: {row.get('public_title', '')}")
    print("\nPOSTS NOT FOUND")
    for row, post_id in not_found:
        print(f"  row {row['_row_number']}: {post_id}")
    print("\nINVALID ROWS")
    for row, reasons in invalid_rows:
        print(f"  row {row['_row_number']}: {', '.join(reasons)}")
    print(f"\nSummary: processed={len(processed)} planned_field_changes={changes_planned} missing_wp_post_id={len(missing_ids)} posts_not_found={len(not_found)} invalid={len(invalid_rows)}")

    if args.apply:
        updated = 0
        for row, post_id, post in processed:
            proposed = proposed_values(row)
            meta = post.get("meta") or {}
            data = {"title": proposed["title"], "meta": {field: proposed[field] for field in META_FIELDS}}
            if current_title(post) != proposed["title"] or any(clean(meta.get(field, "")) != clean(proposed[field]) for field in META_FIELDS):
                wp_request(load_env(), f"/wp-json/wp/v2/grammar/{post_id}", method="POST", data=data)
                updated += 1
        print(f"Applied: updated_posts={updated}")


if __name__ == "__main__":
    main()
