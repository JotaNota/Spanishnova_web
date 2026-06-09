#!/usr/bin/env python3
import argparse

from spanishnova_render.renderer import render_content


def main():
    parser = argparse.ArgumentParser(description="Render SpanishNova content data into Markdown and HTML.")
    parser.add_argument("--type", required=True, choices=["grammar"], help="Content type to render.")
    parser.add_argument("--slug", required=True, help="Roadmap base_slug and content-data filename.")
    args = parser.parse_args()

    markdown_path, html_path = render_content(args.type, args.slug)
    print(f"Rendered Markdown: {markdown_path}")
    print(f"Rendered HTML: {html_path}")


if __name__ == "__main__":
    main()
