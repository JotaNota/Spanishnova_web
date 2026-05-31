# Grammar Content Agent - SpanishNova

Create Spanish grammar lesson source files and clean HTML fragments.

## Read

- `docs/content-system/content-plan/grammar-roadmap.csv`
- `docs/content-system/templates/markdown-posts/grammar-markdown-post-template.md`
- `docs/content-system/templates/html-posts/grammar-html-post-template.md`
- `docs/content-system/content-plan/content-pilot.md` only when the user explicitly selects an item from the manual queue.

Do not read:
- `docs/content-system/templates/html-references/`

## Official Flow

1. Consult `docs/content-system/content-plan/grammar-roadmap.csv` for editorial metadata.
2. Generate Markdown source using `docs/content-system/templates/markdown-posts/grammar-markdown-post-template.md`.
3. Save Markdown source in `docs/content-system/generated/generated-markdown-posts/grammar/`.
4. Read the generated Markdown source.
5. Generate a clean HTML fragment using `docs/content-system/templates/html-posts/grammar-html-post-template.md`.
6. Save the HTML fragment in `docs/content-system/generated/generated-html-posts/grammar/`.

The HTML output is a fragment ready to paste or import as the body content of a `grammar` post.

## Markdown Source

- Markdown is the editable source format.
- Markdown source must be preserved.
- Do not use YAML frontmatter.
- Do not use internal HTML comments.
- Do not include internal metadata inside the lesson body.
- Start the file with the H1 title.
- Keep metadata in `grammar-roadmap.csv`, content logs, PR notes, or publishing checklists, not in the lesson body.

## HTML Fragment

- Generate HTML fragments only, not complete HTML documents.
- Do not generate Gutenberg.
- Do not generate `wp:` blocks.
- Do not generate CSS.
- Do not generate JS.
- Do not generate PHP.
- Do not publish in WordPress.
- Do not include `<!DOCTYPE html>`, `<html>`, `<head>`, or `<body>`.
- Use the HTML fragment template as the structural target.

## Metadata Flow

- Match the user request to `base_slug`, `topic_base`, or `public_title` in `grammar-roadmap.csv`.
- Use the roadmap row for base metadata: `cpt`, `lesson_type`, `level_tax`, `grammar_tax`, `topic_tax`, `default_focus`, `default_variant`, `post_tags`, and `output_folder`.
- Use `public_title` as the visible generated title.
- Use `topic_base` for internal labels and breadcrumbs.
- Do not rebuild `public_title` from `topic_base` unless no `public_title` exists.
- Use the user request for `focus` and `variant`.
- If the user request omits `focus` or `variant`, use `default_focus` and `default_variant` from the roadmap row.
- If both the user request and roadmap row omit `focus` and `variant`, create a broad overview.
- Keep the lesson inside the selected `focus` and `variant` scope.
- Build `slug` from `base_slug` plus `variant` plus `focus` when needed.
- Use the roadmap `public_title` for the visible H1 title.
- Only when `public_title` is missing, build a fallback visible title from `topic_base`, `focus`, and `variant` as needed.
- For verb grammar lessons without `public_title`, use the fallback H1 pattern `{{Verb}} in the {{tense_or_mood}}`.
- Use English tense and mood names in titles.
- Use the Spanish infinitive with initial capital in titles, such as `Necesitar`, `Ser`, or `Ir`.
- Include mood when needed to avoid ambiguity, such as `present indicative`, `present subjunctive`, or `imperfect subjunctive`.
- Do not include level labels in titles.
- Do not use title phrases such as `for Beginners`, `Easy Grammar`, `Beginner Spanish`, `Complete Guide`, or `Mastering`.
- Keep level information in `level_tax`, not in the visible title.
- Do not turn `focus` or `variant` into `grammar_tax` or `topic_tax`.
- Do not invent taxonomy terms.
- Use `post_tags` only for specific labels, non-official categories, proper nouns, or search labels.

## Practice Structure

- Use Spanish section headings for practice areas: `Oraciones`, `Afirmativa`, `Negativa`, `Preguntas`, `Ejercicios`, `Seleccionar`, `Completar`, `Traducir`.
- Do not use English headings for practice sections such as `Affirmative`, `Negative`, `Questions`, `Exercises`, `Multiple Choice`, or `Translation`.
- `Oraciones` uses bullets.
- `Afirmativa` uses bullets.
- `Negativa` uses bullets.
- `Preguntas` uses bullets.
- `Ejercicios` keeps numbering.
- `Seleccionar` keeps numbering.
- `Completar` keeps numbering.
- `Traducir` keeps numbering.
- Use `Completar`, not `Fill in the blank`.
- Add 8 multiple-choice exercises with varied answer positions.
- Add 8 completar exercises.
- Add 8 translation exercises.

## Conjugation Practice

- Use `sn-conjugation-practice` only for `lesson_type=verb-usage` with one simple isolated verb.
- In generated HTML, `sn-conjugation-practice` must be the real HTML block from `docs/content-system/templates/html-posts/grammar-html-post-template.md`.
- The generated HTML must include:
  - `<div class="sn-conjugation-practice">`
  - `<table class="sn-conj-table">`
  - `<button type="button" class="sn-conj-check">Check answers</button>`
- Never generate `sn-conjugation-practice` as plain text.
- Never generate `<p class="sn-conjugation-practice">`.
- Never generate `{{sn_conjugation_practice_marker}}`.
- Do not use `sn-conjugation-practice` in structures, compound phrases, periphrases, or expressions such as `tener que`, `hay algo`, or `he tenido problemas`.

## Language Quality

- Keep explanations short.
- Use English explanations.
- Use Spanish examples.
- Use English translations.
- Spanish examples, questions, exercises, and titles must use correct accents and Spanish punctuation.
- Use opening question marks and exclamation marks where needed.
- In `Overview`, use a bullet example instead of a labeled `Example:` line.

## Lesson Types

- `tense`
- `verb-usage`
- `comparison`
- `structure`

For `tense`, use:
- overview
- structure
- conjugation
- affirmative examples
- negative examples
- questions
- exercises
- wrap up

For `verb-usage`, use:
- overview
- conjugation
- uses
- examples
- exercises
- wrap up

For `comparison`, use:
- comparison
- rules
- contrast examples
- exercises
- wrap up

For `structure`, use:
- overview
- pattern
- examples
- negative examples where useful
- questions where useful
- exercises
- wrap up
