# Vocabulary Content Agent - SpanishNova

Create Spanish vocabulary lesson source files and clean HTML fragments.

## Read

- `docs/content-system/content-plan/vocabulary-roadmap.csv`
- `docs/content-system/templates/markdown-posts/vocabulary-markdown-post-template.md`
- `docs/content-system/templates/html-posts/vocabulary-html-post-template.md`
- `docs/content-system/content-plan/content-pilot.md` only when the user explicitly selects an item from the manual queue.

Do not read:
- `docs/content-system/templates/html-references/`

## Official Flow

1. Consult `docs/content-system/content-plan/vocabulary-roadmap.csv` for editorial metadata.
2. Generate Markdown source using `docs/content-system/templates/markdown-posts/vocabulary-markdown-post-template.md`.
3. Save Markdown source in `docs/content-system/generated/generated-markdown-posts/vocabulary/`.
4. Read the generated Markdown source.
5. Generate a clean HTML fragment using `docs/content-system/templates/html-posts/vocabulary-html-post-template.md`.
6. Save the HTML fragment in `docs/content-system/generated/generated-html-posts/vocabulary/`.

The HTML output is a fragment ready to paste or import as the body content of a `vocabulary` post.

## Markdown Source

- Markdown is the editable source format.
- Markdown source must be preserved.
- Do not use YAML frontmatter.
- Do not use internal HTML comments.
- Do not include internal metadata inside the lesson body.
- Start the file with the H1 title.
- Keep metadata in `vocabulary-roadmap.csv`, content logs, PR notes, or publishing checklists, not in the lesson body.

## HTML Fragment

- Generate HTML fragments only, not complete HTML documents.
- Do not generate Gutenberg.
- Do not generate `wp:` blocks.
- Do not create CSS.
- Do not create JS.
- Do not create PHP.
- Do not publish to WordPress.
- Do not include `<!DOCTYPE html>`, `<html>`, `<head>`, or `<body>`.
- Do not include `sn-breadcrumb`, `sn-meta-row`, or `sn-pill`.
- Breadcrumbs and pills are rendered by `single-vocabulary.php` from `topic_tax` and `level_tax`.
- Use one central sheet with `div.sn-lesson-wrap.sn-vocab-lesson`, `div.sn-lesson-layout`, and `article.sn-lesson-hero.sn-vocab-paper`.
- Do not wrap each section in `sn-panel`.
- Use `<hr>` between major content blocks.
- Visible headings should be in Spanish, with English translation only when it helps learners.
- Use the HTML fragment template as the structural target.

## Metadata Flow

- Match the user request to `base_slug` or `title_base` in `vocabulary-roadmap.csv`.
- Use the roadmap row for base metadata: `cpt`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, and `output_folder`.
- Use the user request for `focus` and `variant`.
- If the user request omits `focus` or `variant`, use `default_focus` and `default_variant` from the roadmap row.
- If both the user request and roadmap row omit `focus` and `variant`, create a broad overview.
- Keep the lesson inside the selected `focus` and `variant` scope.
- Build `slug` from `base_slug` plus `variant` plus `focus` when needed.
- Build `title` from `title_base` plus `focus` and `variant` when needed.
- Do not include level labels in titles.
- Keep level information in `level_tax`, not in the visible title.
- Do not turn `focus` or `variant` into `grammar_tax` or `topic_tax`.
- Do not invent taxonomy terms. Use `post_tags` for non-official categories or specific labels.

## Content Structure

- Use the Markdown template structure.
- Create one lesson per file.
- Group vocabulary by clear categories.
- Categories can have 4–10 terms depending on the topic.
- Use Spanish -> English.
- Use bulleted vocabulary lists with indentation, not vocabulary tables.
- Add common phrases.
- Add one fun phrase with the Spanish in bold and English on the same line.
- Add translation practice.
- Translation exercises must reuse vocabulary, phrases, and structures already introduced earlier in the same lesson. Do not introduce new vocabulary or new situations in `Traducir`; for example, if a body-parts lesson has not introduced `doctor`, do not use `I need a doctor`.
- Add dialogues.
- Use natural scene titles, not `Scene 1`, `Scene 2`, or `Scene 3`.
- Add questions.
- Include `Ejercicios`, `Traducir`, and `Vocabulario por categoría`.
- Avoid dictionary-only pages.
