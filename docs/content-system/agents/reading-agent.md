# Reading Content Agent - SpanishNova

Create Spanish reading lesson source files and clean HTML fragments.

## Read

- `docs/content-system/content-plan/reading-roadmap.csv`
- `docs/content-system/templates/markdown-posts/reading-markdown-post-template.md`
- `docs/content-system/templates/html-posts/reading-html-post-template.md`
- `docs/content-system/content-plan/content-pilot.md` only when the user explicitly selects an item from the manual queue.

Do not read:
- `docs/content-system/templates/html-references/`

## Official Flow

1. Consult `docs/content-system/content-plan/reading-roadmap.csv` for editorial metadata.
2. Generate Markdown source using `docs/content-system/templates/markdown-posts/reading-markdown-post-template.md`.
3. Save Markdown source in `docs/content-system/generated/generated-markdown-posts/readings/`.
4. Read the generated Markdown source.
5. Generate a clean HTML fragment using `docs/content-system/templates/html-posts/reading-html-post-template.md`.
6. Save the HTML fragment in `docs/content-system/generated/generated-html-posts/readings/`.

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

- Match the user request to `slug` or `title` in `reading-roadmap.csv`.
- Use the roadmap row for base metadata: `cpt`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, `reading_variant`, and `output_path`.
- Use `reading_variant` to choose the lesson structure.
- Do not print `reading_variant` in the lesson body.
- Keep the reading inside the selected `reading_variant` scope.
- Do not include level labels in titles.
- Keep level information in `level_tax`, not in the visible title.
- Do not turn `reading_variant` into `grammar_tax` or `topic_tax`.
- Do not invent taxonomy terms. Use `post_tags` for non-official categories or specific labels.

## Reading Variants

### `narrative_profile`

Use for biographies, historical figures, discoveries, and stories about one person or group.

Structure:
- Brief English intro.
- Key vocabulary before the reading.
- Four narrative sections.
- Each section should have two short paragraphs.
- Each paragraph should have two to four sentences.
- A short final section may use one paragraph when needed.
- Key ideas.
- Activities.

Use section headings that move the story forward. Do not create small headings for isolated explanatory questions.

### `practical_situation`

Use for daily-life readings, travel scenes, work situations, food places, neighborhoods, and simple personal experiences.

Structure:
- Brief English intro.
- Key vocabulary before the reading.
- Three or four scene sections.
- Each section should have one or two short paragraphs.
- Include useful phrases inside the scene, not as disconnected notes.
- Key ideas.
- Activities.

Keep the language concrete and useful for learners.

### `procedural_set`

Use for recipes, routines, instructions, comparisons of similar processes, and grouped how-to lessons.

Structure:
- Brief English intro.
- Key vocabulary or ingredients before the main content.
- Three or four grouped items.
- Each item should include a short ingredient/material list and a preparation/process section.
- Add a verb list only when the lesson depends on repeated actions.
- Key ideas.
- Activities.

Keep each grouped item parallel in length and shape.

### `curiosity_article`

Use for animal facts, culture notes, science curiosities, expressions, and informative articles.

Structure:
- Brief English intro.
- Key vocabulary before the reading.
- Four or five thematic sections.
- Each section should have one or two short paragraphs.
- Use bullets only for lists of facts or examples.
- Key ideas.
- Activities.

Do not repeat headings. Do not include an index inside the lesson body.

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
