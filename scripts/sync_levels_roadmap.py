#!/usr/bin/env python3
import argparse
import csv
import html
import re
import sys
import urllib.parse
import zipfile
from collections import Counter
from pathlib import Path
from xml.etree import ElementTree

from spanishnova_upload.config import ROOT, load_env
from spanishnova_upload.wordpress import wp_request


CSV_PATH = ROOT / "docs/content-system/roadmaps/Levels-roadmap.csv"
WORKBOOK_PATH = ROOT / "docs/content-system/roadmaps/Levels-roadmap.csv.xlsx"
SHEET_NAME = "Grammar Roadmap"
REQUIRED_COLUMNS = [
    "Level",
    "Level Order",
    "Module",
    "Existing Lesson ID",
]
REJECTED_ACTIONS = {"create", "merge", "split"}
ROADMAP_SOURCE_MARKER = "levels-roadmap-current"
MAIN_NS = "http://schemas.openxmlformats.org/spreadsheetml/2006/main"
DOC_REL_NS = "http://schemas.openxmlformats.org/officeDocument/2006/relationships"
PKG_REL_NS = "http://schemas.openxmlformats.org/package/2006/relationships"


def parse_args():
    parser = argparse.ArgumentParser(
        description="Sync level_tax, level_module, and level_order from the levels roadmap CSV."
    )
    mode = parser.add_mutually_exclusive_group(required=True)
    mode.add_argument("--dry-run", action="store_true", help="Report changes without writing to WordPress.")
    mode.add_argument("--apply", action="store_true", help="Write validated changes to WordPress.")
    return parser.parse_args()


def column_index(cell_reference):
    letters = re.match(r"[A-Z]+", cell_reference or "")
    if not letters:
        return 0

    index = 0
    for letter in letters.group(0):
        index = index * 26 + ord(letter) - ord("A") + 1
    return index - 1


def normalize_target(target):
    target = target.replace("\\", "/")
    if target.startswith("/"):
        return target.lstrip("/")
    if target.startswith("xl/"):
        return target
    return "xl/" + target.lstrip("/")


def read_shared_strings(archive):
    try:
        root = ElementTree.fromstring(archive.read("xl/sharedStrings.xml"))
    except KeyError:
        return []

    strings = []
    for item in root.findall(f"{{{MAIN_NS}}}si"):
        strings.append("".join(node.text or "" for node in item.iter(f"{{{MAIN_NS}}}t")))
    return strings


def cell_value(cell, shared_strings):
    cell_type = cell.get("t")
    if cell_type == "inlineStr":
        return "".join(node.text or "" for node in cell.iter(f"{{{MAIN_NS}}}t"))

    value_node = cell.find(f"{{{MAIN_NS}}}v")
    if value_node is None or value_node.text is None:
        return ""

    raw = value_node.text
    if cell_type == "s":
        return shared_strings[int(raw)]
    if cell_type in {"str", "b"}:
        return raw

    try:
        number = float(raw)
        return int(number) if number.is_integer() else number
    except ValueError:
        return raw


def read_xlsx_rows(path, sheet_name):
    if not path.exists():
        raise SystemExit(f"Missing workbook: {path}")

    with zipfile.ZipFile(path) as archive:
        workbook_root = ElementTree.fromstring(archive.read("xl/workbook.xml"))
        relations_root = ElementTree.fromstring(archive.read("xl/_rels/workbook.xml.rels"))
        relationships = {
            relation.get("Id"): normalize_target(relation.get("Target", ""))
            for relation in relations_root.findall(f"{{{PKG_REL_NS}}}Relationship")
        }

        sheet_path = None
        for sheet in workbook_root.findall(f".//{{{MAIN_NS}}}sheet"):
            if sheet.get("name") == sheet_name:
                sheet_path = relationships.get(sheet.get(f"{{{DOC_REL_NS}}}id"))
                break

        if not sheet_path:
            raise SystemExit(f"Missing worksheet: {sheet_name}")

        shared_strings = read_shared_strings(archive)
        sheet_root = ElementTree.fromstring(archive.read(sheet_path))
        matrix = []

        for row in sheet_root.findall(f".//{{{MAIN_NS}}}row"):
            values = {}
            for cell in row.findall(f"{{{MAIN_NS}}}c"):
                values[column_index(cell.get("r"))] = cell_value(cell, shared_strings)
            if values:
                width = max(values) + 1
                matrix.append([values.get(index, "") for index in range(width)])

    if not matrix:
        raise SystemExit(f"Worksheet is empty: {sheet_name}")

    headers = [str(value).strip() for value in matrix[0]]
    missing = [column for column in REQUIRED_COLUMNS if column not in headers]
    if missing:
        raise SystemExit("Missing required columns: " + ", ".join(missing))

    rows = []
    for row_number, values in enumerate(matrix[1:], start=2):
        padded = values + [""] * (len(headers) - len(values))
        row = {header: padded[index] for index, header in enumerate(headers)}
        if not any(clean_text(value) for value in row.values()):
            continue
        row["_row_number"] = row_number
        rows.append(row)
    return rows


def read_csv_rows(path):
    if not path.exists():
        raise SystemExit(f"Missing CSV: {path}")

    with path.open("r", encoding="utf-8-sig", newline="") as source:
        reader = csv.DictReader(source)
        headers = [clean_text(header) for header in (reader.fieldnames or [])]
        missing = [column for column in REQUIRED_COLUMNS if column not in headers]
        if missing:
            raise SystemExit("Missing required columns: " + ", ".join(missing))

        rows = []
        for row_number, values in enumerate(reader, start=2):
            row = {header: values.get(header, "") for header in headers}
            if not any(clean_text(value) for value in row.values()):
                continue
            row["_row_number"] = row_number
            rows.append(row)
    return rows


def roadmap_source():
    if CSV_PATH.exists():
        return CSV_PATH, read_csv_rows(CSV_PATH)
    if WORKBOOK_PATH.exists():
        return WORKBOOK_PATH, read_xlsx_rows(WORKBOOK_PATH, SHEET_NAME)
    raise SystemExit(f"Missing roadmap source: {CSV_PATH}")


def clean_text(value):
    return str(value if value is not None else "").strip()


def parse_single_post_id(value):
    text = clean_text(value)
    if not re.fullmatch(r"[1-9]\d*", text):
        return None
    return int(text)


def parse_order(value):
    if isinstance(value, int):
        return value
    if isinstance(value, float) and value.is_integer():
        return int(value)

    text = clean_text(value)
    if not re.fullmatch(r"-?\d+", text):
        return None
    return int(text)


def display_title(post):
    rendered = post.get("title", {}).get("rendered", "")
    return html.unescape(re.sub(r"<[^>]+>", "", rendered)).strip()


def fetch_grammar_posts(env, post_ids):
    if not post_ids:
        return {}

    query = urllib.parse.urlencode(
        {
            "include": ",".join(str(post_id) for post_id in sorted(post_ids)),
            "status": "any",
            "context": "edit",
            "per_page": 100,
        }
    )
    posts = wp_request(env, f"/wp-json/wp/v2/grammar?{query}")
    return {int(post["id"]): post for post in posts}


def fetch_level_terms(env):
    terms = wp_request(env, "/wp-json/wp/v2/level_tax?per_page=100&hide_empty=false")
    return {clean_text(term.get("name")).casefold(): term for term in terms}


def classify_rows(rows, posts_by_id, level_terms):
    candidates = []
    ignored = []
    invalid = []
    unmapped = []

    for row in rows:
        raw_id = clean_text(row.get("Existing Lesson ID"))
        action = clean_text(row.get("Match Action"))
        level = clean_text(row.get("Level"))
        module = clean_text(row.get("Module"))
        order = parse_order(row.get("Level Order"))
        post_id = parse_single_post_id(raw_id)
        invalid_reasons = []

        if action.casefold() in REJECTED_ACTIONS:
            ignored.append((row, f"Match Action is {action}"))
            continue
        if not level:
            invalid_reasons.append("Level is empty")
        if not module:
            invalid_reasons.append("Module is empty")
        if order is None:
            invalid_reasons.append("Level Order is not an integer")
        if level and level.casefold() not in level_terms:
            invalid_reasons.append("Level term does not exist in level_tax")

        item = {
            "row": row["_row_number"],
            "raw_id": raw_id,
            "post_id": post_id,
            "post": posts_by_id.get(post_id),
            "level": level,
            "module": module,
            "order": order,
            "global_order": parse_order(row.get("Global Order")),
            "match_action": action,
            "reasons": invalid_reasons,
        }

        if invalid_reasons:
            invalid.append(item)
            continue
        if post_id is None:
            item["reasons"] = ["Existing Lesson ID is not one integer"]
            unmapped.append(item)
            continue
        if post_id not in posts_by_id:
            item["reasons"] = ["ID does not resolve to a grammar post"]
            unmapped.append(item)
            continue
        expected_term_id = int(level_terms[level.casefold()]["id"])
        current_term_ids = {int(term_id) for term_id in posts_by_id[post_id].get("level_tax", [])}
        if expected_term_id not in current_term_ids:
            item["reasons"] = ["Post is not assigned to the CSV Level in level_tax"]
            invalid.append(item)
            continue
        candidates.append(item)

    duplicate_ids = {post_id for post_id, count in Counter(item["post_id"] for item in candidates).items() if count > 1}
    valid = []
    for item in candidates:
        if item["post_id"] in duplicate_ids:
            item["reasons"] = ["Post ID appears in multiple otherwise-valid rows"]
            invalid.append(item)
        else:
            valid.append(item)

    return valid, ignored, invalid, unmapped


def operation_for(item, level_terms):
    post = item["post"]
    current_meta = post.get("meta") or {}
    unchanged = (
        clean_text(current_meta.get("level_module")) == item["module"]
        and parse_order(current_meta.get("level_order")) == item["order"]
        and clean_text(current_meta.get("level_roadmap_source")) == ROADMAP_SOURCE_MARKER
    )
    return "NO CHANGE" if unchanged else "UPDATE"


def print_items(heading, items):
    print()
    print(heading)
    print("Row | Existing Lesson ID | Level | Module | Level Order | Reason")
    for item in items:
        if isinstance(item, tuple):
            row, reason = item
            print(
                f"{row['_row_number']} | {clean_text(row.get('Existing Lesson ID'))} | "
                f"{clean_text(row.get('Level'))} | {clean_text(row.get('Module'))} | "
                f"{clean_text(row.get('Level Order'))} | {reason}"
            )
            continue
        print(
            f"{item['row']} | {item['raw_id']} | {item['level']} | {item['module']} | "
            f"{item['order'] if item['order'] is not None else ''} | {'; '.join(item['reasons'])}"
        )


def print_report(valid, ignored, invalid, unmapped, level_terms, mode, source_path):
    print(f"Mode: {mode}")
    print(f"Source: {source_path}")
    print()
    print("VALID")
    print("Post ID | Current title | Level | Module | Level Order | Action")
    for item in sorted(valid, key=lambda value: value["order"]):
        operation = operation_for(item, level_terms)
        print(
            f"{item['post_id']} | {display_title(item['post'])} | {item['level']} | "
            f"{item['module']} | {item['order']} | {operation} ({item['match_action']})"
        )

    print_items("IGNORED", sorted(ignored, key=lambda value: value[0]["_row_number"]))
    print_items("INVALID", sorted(invalid, key=lambda value: value["row"]))
    print_items("UNMAPPED", sorted(unmapped, key=lambda value: value["row"]))

    print()
    print(
        f"Summary: processed={len(valid)} ignored={len(ignored)} invalid={len(invalid)} "
        f"unmapped={len(unmapped)} total={len(valid) + len(ignored) + len(invalid) + len(unmapped)}"
    )


def apply_updates(env, valid, level_terms):
    updated = 0
    unchanged = 0

    for item in sorted(valid, key=lambda value: value["order"]):
        operation = operation_for(item, level_terms)
        if operation == "NO CHANGE":
            unchanged += 1
            continue

        wp_request(
            env,
            f"/wp-json/wp/v2/grammar/{item['post_id']}",
            method="POST",
            data={
                "meta": {
                    "level_module": item["module"],
                    "level_order": item["order"],
                    "level_roadmap_source": ROADMAP_SOURCE_MARKER,
                },
            },
        )
        updated += 1

    print(f"Applied: updated={updated} unchanged={unchanged}")


def main():
    args = parse_args()
    source_path, rows = roadmap_source()
    raw_ids = {post_id for row in rows if (post_id := parse_single_post_id(row.get("Existing Lesson ID"))) is not None}
    env = load_env()
    posts_by_id = fetch_grammar_posts(env, raw_ids)
    level_terms = fetch_level_terms(env)
    valid, ignored, invalid, unmapped = classify_rows(rows, posts_by_id, level_terms)
    mode = "DRY RUN" if args.dry_run else "APPLY"
    print_report(valid, ignored, invalid, unmapped, level_terms, mode, source_path)

    if args.apply:
        apply_updates(env, valid, level_terms)


if __name__ == "__main__":
    main()
