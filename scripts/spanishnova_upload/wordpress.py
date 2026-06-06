import base64
import json
import urllib.parse
import urllib.request
from urllib.error import HTTPError, URLError


def auth_header(env):
    token = f"{env['WP_USERNAME']}:{env['WP_APP_PASSWORD']}".encode("utf-8")
    return "Basic " + base64.b64encode(token).decode("ascii")


def wp_request(env, path, method="GET", data=None):
    base_url = env["WP_BASE_URL"].rstrip("/")
    url = f"{base_url}{path}"
    body = None

    if data is not None:
        body = json.dumps(data).encode("utf-8")

    req = urllib.request.Request(url, data=body, method=method)
    req.add_header("Authorization", auth_header(env))
    req.add_header("Accept", "application/json")

    if body is not None:
        req.add_header("Content-Type", "application/json")

    try:
        with urllib.request.urlopen(req, timeout=20) as res:
            return json.loads(res.read().decode("utf-8-sig"))
    except HTTPError as e:
        error_body = e.read().decode("utf-8-sig", errors="replace")
        raise SystemExit(f"HTTP {e.code}: {error_body}")
    except URLError as e:
        raise SystemExit(f"Connection error: {e.reason}")


def get_term_id(env, taxonomy, name, create=False):
    query = urllib.parse.urlencode({"search": name})
    existing = wp_request(env, f"/wp-json/wp/v2/{taxonomy}?{query}")

    for term in existing:
        if term.get("name", "").lower() == name.lower() or term.get("slug", "").lower() == name.lower():
            return term["id"]

    if not create:
        raise SystemExit(f"Missing {taxonomy} term in WordPress: {name}")

    created = wp_request(env, f"/wp-json/wp/v2/{taxonomy}", method="POST", data={"name": name})
    return created["id"]
