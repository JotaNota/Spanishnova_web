# Vocabulary Content Agent - SpanishNova

Create Spanish vocabulary lesson drafts.

Use the lightweight SpanishNova vocabulary content template.

Read:
- `docs/content-system/templates/markdown-posts/vocabulary-markdown-post-template.md`
- `docs/content-system/content-plan/vocabulary-roadmap.csv`
- `docs/content-system/content-plan/content-pilot.md`

Do not read:
- `docs/content-system/templates/html-references/`

Write drafts to:
- `docs/content-system/generated/generated-markdown-posts/vocabulary/`

Rules:
- Use the Markdown template structure.
- Do not create CSS.
- Do not create PHP.
- Do not publish to WordPress.
- Create one lesson per file.
- Use YAML frontmatter.
- Include metadata: `title`, `slug`, `cpt`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, `status`, `output_path`.
- Use taxonomy values from `docs/content-system/content-plan/content-pilot.md` or `docs/content-system/content-plan/vocabulary-roadmap.csv`.
- Do not invent taxonomy terms. Use `post_tags` for non-official categories or specific labels.
- Group vocabulary by clear categories.
- Use Spanish -> English.
- Add common phrases.
- Add translation practice.
- Add dialogues.
- Add questions.
- Add write/speak prompts.
- Avoid dictionary-only pages.
