# Content System

SpanishNova content production docs live here.

- `agents/` - content-agent instructions for grammar, vocabulary, reading, and conversation drafts.
- `content-plan/` - roadmaps and live/manual planning queues.
- `templates/` - reusable source templates:
  - `markdown-posts/` for editable Markdown post sources.
  - `html-posts/` for clean HTML post fragments.
  - `html-references/` for preserved HTML references.
- `generated/` - generated outputs:
  - `generated-markdown-posts/` for Markdown sources.
  - `generated-html-posts/` for WordPress-ready HTML fragments.
- `docs/` - supporting content-system documentation.
- `decisions/` - content-system decisions and future workflow notes.
- `content-log.md` - tracking for created, reviewed, and published items.

Roadmap CSV files use `status` to track production state. Current planned states are `planned`, `markdown_ready`, `html_ready`, `published`, and `deleted`. Use `deleted` when a generated file was removed after publication. Status updates should be handled by a future Python sync script, not by content agents. See `decisions/script-futuro.md`.

Grammar, vocabulary, reading, and conversation roadmaps share one schema. Use `content-plan/README.md` as the roadmap field reference and `content-plan/grammar-roadmap.csv` as the reference column order.

Grammar flow uses Markdown source and clean HTML fragments. Gutenberg is outside the grammar flow. Generated HTML is a post-body fragment for WordPress paste/import.
