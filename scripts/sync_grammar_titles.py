#!/usr/bin/env python3
import argparse
import csv
import subprocess
import sys
from pathlib import Path

THEME_ROOT = Path(__file__).resolve().parents[1]
ROADMAP = THEME_ROOT / "docs/content-system/roadmaps/grammar-roadmap.csv"


def find_wp_root() -> Path | None:
    for directory in [THEME_ROOT, *THEME_ROOT.parents]:
        if (directory / "wp-config.php").is_file():
            return directory
    return None


def wp(wp_root: Path, *args: str) -> str:
    command = ["wp", f"--path={wp_root}", *args, "--skip-plugins", "--skip-themes"]
    result = subprocess.run(command, text=True, capture_output=True)

    if result.returncode != 0:
        raise RuntimeError(result.stderr.strip() or result.stdout.strip())

    return result.stdout.strip()


def main():
    parser = argparse.ArgumentParser(
        description="Simula o actualiza solo post_title desde grammar-roadmap.csv."
    )
    parser.add_argument(
        "--apply",
        action="store_true",
        help="Aplica los cambios. Sin esta opción, solo muestra la simulación.",
    )
    parser.add_argument(
        "--post-type",
        default="grammar",
        help="Tipo de post esperado. Por defecto: grammar.",
    )
    args = parser.parse_args()

    if not ROADMAP.is_file():
        sys.exit(f"No existe el roadmap: {ROADMAP}")

    wp_root = find_wp_root()
    if not wp_root:
        sys.exit(
            "No encontré wp-config.php desde esta carpeta. "
            "Ejecutá el script desde el tema dentro de LocalWP."
        )

    try:
        wp(wp_root, "core", "is-installed")
    except FileNotFoundError:
        sys.exit(
            "No encuentro el comando 'wp'. Verificá que WP-CLI esté instalado "
            "y disponible en esta terminal."
        )
    except RuntimeError as error:
        sys.exit(f"No pude conectar con WordPress Local:\n{error}")

    with ROADMAP.open(encoding="utf-8-sig", newline="") as file:
        rows = list(csv.DictReader(file))

    required_columns = {"wp_post_id", "public_title"}
    missing_columns = required_columns - set(rows[0] if rows else [])
    if missing_columns:
        sys.exit(
            "Faltan columnas en el CSV: " + ", ".join(sorted(missing_columns))
        )

    updates = 0
    issues = 0

    print(f"Roadmap: {ROADMAP}")
    print(f"WordPress: {wp_root}")
    print("Modo: APPLY" if args.apply else "Modo: DRY-RUN")
    print()

    for line_number, row in enumerate(rows, start=2):
        post_id = (row.get("wp_post_id") or "").strip()
        new_title = (row.get("public_title") or "").strip()

        if not post_id:
            print(f"SKIP línea {line_number}: wp_post_id vacío")
            issues += 1
            continue

        if not post_id.isdigit():
            print(f"SKIP línea {line_number}: wp_post_id inválido: {post_id}")
            issues += 1
            continue

        if not new_title:
            print(f"SKIP ID {post_id}: public_title vacío")
            issues += 1
            continue

        try:
            post_type = wp(wp_root, "post", "get", post_id, "--field=post_type")
            current_title = wp(wp_root, "post", "get", post_id, "--field=post_title")
        except RuntimeError:
            print(f"SKIP ID {post_id}: no existe en WordPress")
            issues += 1
            continue

        if post_type != args.post_type:
            print(
                f"SKIP ID {post_id}: post_type es '{post_type}', "
                f"se esperaba '{args.post_type}'"
            )
            issues += 1
            continue

        if current_title == new_title:
            print(f"OK   ID {post_id}: ya tiene el título correcto")
            continue

        print(f"CHANGE ID {post_id}")
        print(f"  Actual:    {current_title}")
        print(f"  Propuesto: {new_title}")

        if args.apply:
            try:
                wp(wp_root, "post", "update", post_id, f"--post_title={new_title}")
                print("  Aplicado.")
            except RuntimeError as error:
                print(f"  ERROR: {error}")
                issues += 1
                continue

        updates += 1

    print()
    print(f"Resumen: {updates} cambio(s), {issues} problema(s).")

    if not args.apply:
        print("No se modificó WordPress. Revisá la lista antes de usar --apply.")


if __name__ == "__main__":
    main()
