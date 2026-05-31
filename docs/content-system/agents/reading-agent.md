# Reading Content Agent - SpanishNova

Create Spanish reading lesson source files and clean HTML fragments.

## Read

- `docs/content-system/content-plan/reading-roadmap.csv`
- `docs/content-system/templates/reading-structures/reading-structure-variants.md`
- `docs/content-system/templates/markdown-posts/reading-markdown-post-template.md`
- `docs/content-system/templates/html-posts/reading-html-post-template.md`
- `docs/content-system/content-plan/content-pilot.md` only when the user explicitly selects an item from the manual queue.

Do not read:
- `docs/content-system/templates/html-references/`

## Official Flow

1. Consult `docs/content-system/content-plan/reading-roadmap.csv` for editorial metadata.
2. Use `default_variant` to select the matching skeleton from `docs/content-system/templates/reading-structures/reading-structure-variants.md`.
3. Generate Markdown source using `docs/content-system/templates/markdown-posts/reading-markdown-post-template.md`.
4. Save Markdown source in `docs/content-system/generated/generated-markdown-posts/readings/`.
5. Read the generated Markdown source.
6. Generate a clean HTML fragment using `docs/content-system/templates/html-posts/reading-html-post-template.md`.
7. Save the HTML fragment in `docs/content-system/generated/generated-html-posts/readings/`.

The HTML output is a fragment ready to paste or import as the body content of a `readings` post.

## Markdown Source

- Markdown is the editable source format.
- Markdown source must be preserved.
- Do not use YAML frontmatter.
- Do not use internal HTML comments.
- Do not include internal metadata inside the lesson body.
- Start the file with the H1 title.
- Keep metadata in `reading-roadmap.csv`, content logs, PR notes, or publishing checklists, not in the lesson body.

## HTML Fragment

- Generate HTML fragments only, not complete HTML documents.
- Do not generate Gutenberg.
- Do not generate `wp:` blocks.
- Do not create CSS.
- Do not create JS.
- Do not create PHP.
- Do not publish to WordPress.
- Do not include `<!DOCTYPE html>`, `<html>`, `<head>`, or `<body>`.
- Use the HTML fragment template as the structural target.

## Metadata Flow

- Match the user request to `base_slug` or `public_title` in `reading-roadmap.csv`.
- Use the roadmap row for external metadata: `cpt`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, and `output_folder`.
- Use `public_title` as the visible generated title.
- Use `default_variant` only to choose the lesson structure from `reading-structure-variants.md`.
- Do not print `default_variant` in the lesson body.
- Do not print external metadata in the lesson body.
- Keep the reading inside the selected `default_variant` scope.
- Build the Markdown filename as `output_folder + base_slug + .md`.
- Build the HTML filename by mirroring `base_slug` in `docs/content-system/generated/generated-html-posts/readings/`.
- Do not include level labels in titles.
- Keep level information in `level_tax`, not in the visible title.
- Do not turn `default_variant` into `grammar_tax` or `topic_tax`.
- Do not invent taxonomy terms. Use `post_tags` for non-official categories or specific labels.

## Content Structure

- Create one reading per file.
- Write natural Spanish.
- Match the selected level.
- Use short paragraphs.
- Keep section rhythm consistent inside each reading.
- Add key vocabulary before the reading.
- Use vocabulary, phrases, and facts already introduced before asking exercises about them.
- Add true/false questions.
- Add reading comprehension questions.
- Add personal opinion questions.
- Avoid long academic text.
