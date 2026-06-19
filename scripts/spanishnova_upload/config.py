from pathlib import Path


ROOT = Path(__file__).resolve().parents[2]
ENV_FILE = ROOT / ".env.local"
HTML_ROOT = ROOT / "docs/content-system/generated/generated-html-posts"

ROADMAPS = {
    "grammar": ROOT / "docs/content-system/content-plan/grammar-roadmap.csv",
    "vocabulary": ROOT / "docs/content-system/content-plan/vocabulary-roadmap.csv",
}

ROADMAP = ROADMAPS["grammar"]


def load_env():
    env = {}
    if not ENV_FILE.exists():
        raise SystemExit("Missing .env.local")

    for line in ENV_FILE.read_text(encoding="utf-8").splitlines():
        line = line.strip()
        if not line or line.startswith("#") or "=" not in line:
            continue
        key, value = line.split("=", 1)
        env[key.strip()] = value.strip()

    for key in ["WP_BASE_URL", "WP_USERNAME", "WP_APP_PASSWORD"]:
        if not env.get(key):
            raise SystemExit(f"Missing {key} in .env.local")

    return env
