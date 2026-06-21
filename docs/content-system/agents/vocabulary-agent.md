# Vocabulary Content Agent - SpanishNova

Create Spanish vocabulary lesson source files and clean HTML fragments.

## Read

- `docs/content-system/roadmaps/vocabulary-roadmap.csv`
- `docs/content-system/templates/markdown-posts/vocabulary-markdown-post-template.md`
- `docs/content-system/templates/html-posts/vocabulary-html-post-template.md`
- `docs/content-system/roadmaps/content-pilot.md` only when the user explicitly selects an item from the manual queue.

Do not read:
- `docs/content-system/templates/html-references/`

## Official Flow

1. Consult `docs/content-system/roadmaps/vocabulary-roadmap.csv` for editorial metadata.
2. Generate Markdown source using `docs/content-system/templates/markdown-posts/vocabulary-markdown-post-template.md`.
3. Save Markdown source in `docs/content-system/generated/markdown/vocabulary/`.
4. Read the generated Markdown source.
5. Generate a clean HTML fragment using `docs/content-system/templates/html-posts/vocabulary-html-post-template.md`.
6. Save the HTML fragment in `docs/content-system/generated/html/vocabulary/`.

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

- Match the user request to `base_slug` or `public_title` in `vocabulary-roadmap.csv`.
- Use the roadmap row for external metadata: `cpt`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, and `output_folder`.
- Use `public_title` as the visible generated title.
- Use `vocabulary_variant` as an optional editorial structure variant when it is present.
- Do not print external metadata or `vocabulary_variant` in the lesson body.
- Build the Markdown filename as `output_folder + base_slug + .md`.
- Build the HTML filename by mirroring `base_slug` in `docs/content-system/generated/html/vocabulary/`.
- Do not include level labels in titles.
- Keep level information in `level_tax`, not in the visible title.
- Do not turn `vocabulary_variant` into `grammar_tax` or `topic_tax`.
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
