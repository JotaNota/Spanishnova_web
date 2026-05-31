# Conversation Content Agent - SpanishNova

Create Spanish conversation lesson source files and clean HTML fragments.

## Read

- `docs/content-system/content-plan/conversation-roadmap.csv`
- `docs/content-system/templates/conversation-structures/conversation-structure-variants.md`
- `docs/content-system/templates/markdown-posts/conversation-markdown-post-template.md`
- `docs/content-system/templates/html-posts/conversation-html-post-template.md`
- `docs/content-system/content-plan/content-pilot.md` only when the user explicitly selects an item from the manual queue.

Do not read:
- `docs/content-system/templates/html-references/`

## Official Flow

1. Consult `docs/content-system/content-plan/conversation-roadmap.csv` for editorial metadata and `default_variant`.
2. Read the matching variant in `docs/content-system/templates/conversation-structures/conversation-structure-variants.md`.
3. Generate Markdown source using `docs/content-system/templates/markdown-posts/conversation-markdown-post-template.md`.
4. Save Markdown source in `docs/content-system/generated/generated-markdown-posts/conversations/`.
5. Read the generated Markdown source.
6. Generate a clean HTML fragment using `docs/content-system/templates/html-posts/conversation-html-post-template.md`.
7. Save the HTML fragment in `docs/content-system/generated/generated-html-posts/conversations/`.

The HTML output is a fragment ready to paste or import as the body content of a `conversations` post.

## Markdown Source

- Markdown is the editable source format.
- Markdown source must be preserved.
- Do not use YAML frontmatter.
- Do not use internal HTML comments.
- Do not include internal metadata inside the lesson body.
- Start the file with the H1 title.
- Keep metadata in `conversation-roadmap.csv`, content logs, PR notes, or publishing checklists, not in the lesson body.

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

- Match the user request to `base_slug`, `topic_base`, or `public_title` in `conversation-roadmap.csv`.
- Use the roadmap row for base metadata: `cpt`, `lesson_type`, `level_tax`, `grammar_tax`, `topic_tax`, `default_focus`, `default_variant`, `post_tags`, and `output_folder`.
- Use `public_title` as the visible generated title and `topic_base` as the internal topic label.
- Use `default_variant` to select the matching structure in `conversation-structure-variants.md`.
- Do not print `default_variant` in the lesson body.
- Keep the conversation inside the selected topic and variant.
- Build the Markdown filename as `output_folder + base_slug + .md`.
- Build the HTML filename by mirroring `base_slug` in `docs/content-system/generated/generated-html-posts/conversations/`.
- Do not include level labels in titles.
- Keep level information in `level_tax`, not in the visible title.
- Do not turn `default_variant` into `grammar_tax` or `topic_tax`.
- Do not invent taxonomy terms. Use `post_tags` for non-official categories or specific labels.

## Content Structure

- Use the selected variant as the editorial structure.
- Use the Markdown template as the editable source pattern.
- Create one conversation per file.
- Use natural Spanish.
- Use clear speaker labels.
- Add useful vocabulary.
- Add common phrases.
- Add translation practice.
- Add roleplay prompts.
- Add discussion questions.
- Avoid robotic dialogue.
