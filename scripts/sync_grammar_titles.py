#!/usr/bin/env python3
import argparse
import csv
import html
import os
import re
import sys
from pathlib import Path
from urllib import request, error
from base64 import b64encode

ROOT = Path(__file__).resolve().parents[1]
ROADMAP = ROOT / "docs" / "content-system" / "content-plan" / "grammar-roadmap.csv"
ENV_FILE = ROOT / ".env.local"

def env_value(name):
    if ENV_FILE.exists():
        for line in ENV_FILE.read_text(encoding="utf-8").splitlines():
            if line.startswith(name + "="):
                return line.split("=", 1)[1].strip().strip('"').strip("'")
    return os.environ.get(name, "")

WP_URL = env_value("WP_URL").rstrip("/")
WP_USER = env_value("WP_USER")
WP_PASSWORD = env_value("WP_PASSWORD")

if not WP_URL or not WP_USER or not WP_PASSWORD:
    sys.exit("Falta WP_URL, WP_USER o WP_PASSWORD en .env.local")

if not ROADMAP.exists():
    sys.exit(f"No existe el roadmap: {ROADMAP}")

def wp_api(path, method="GET", data=None):
    headers = {
        "Authorization": "Basic " + b64encode(
            f"{WP_USER}:{WP_PASSWORD}".encode()
        ).decode()
    }

    if data is not None:
        data = "&".join(
            f"{request.quote(str(k))}={request.quote(str(v))}"
            for k, v in data.items()
        ).encode()
        headers["Content-Type"] = "application/x-www-form-urlencoded"

    req = request.Request(
        f"{WP_URL}{path}",
        data=data,
        headers=headers,
        method=method,
    )

    try:
        with request.urlopen(req) as response:
            import json
            return json.loads(response.read().decode("utf-8"))
    except error.HTTPError as exc:
        sys.exit(f"WordPress error {exc.code}: {exc.read().decode('utf-8', 'replace')}")

def clean_title(post):
    title = post.get("title", {})
    value = title.get("raw") or title.get("rendered") or ""
    return html.unescape(re.sub(r"<[^>]+>", "", value)).strip()

parser = argparse.ArgumentParser()
parser.add_argument("--apply", action="store_true")
args = parser.parse_args()

with ROADMAP.open(encoding="utf-8-sig", newline="") as file:
    rows = list(csv.DictReader(file))

changes = 0

for row in rows:
    post_id = (row.get("wp_post_id") or "").strip()
    new_title = (row.get("public_title") or "").strip()

    if not post_id or not new_title:
        continue

    post = wp_api(f"/wp-json/wp/v2/grammar/{post_id}?context=edit")
    old_title = clean_title(post)

    if old_title == new_title:
        continue

    changes += 1
    print(f"{post_id} | {old_title} -> {new_title}")

    if args.apply:
        wp_api(
            f"/wp-json/wp/v2/grammar/{post_id}",
            method="POST",
            data={"title": new_title},
        )

print(f"\nCambios: {changes}")
print("Aplicados." if args.apply else "Simulación. Usa --apply para aplicar.")
