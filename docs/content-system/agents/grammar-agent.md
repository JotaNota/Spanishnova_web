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
- Keep generated editorial HTML simple: `section`, `h2`, `h3`, `p`, `ul`, `ol`, `li`, `table`, `details`, `summary`, `strong`, and `em`.
- Do not use layout or presentation wrappers such as `two-column`, `example-group`, `example-bubble`, or `exercise-block`.
- The only functional exception to simple editorial HTML is the real `sn-conjugation-practice` block for isolated verb lessons.

## Metadata Flow

- Match the user request to `base_slug`, `topic_base`, or `public_title` in `grammar-roadmap.csv`.
- Use the roadmap row for external metadata and generation defaults: `cpt`, `lesson_type`, `level_tax`, `grammar_tax`, `topic_tax`, `default_focus`, `default_variant`, `post_tags`, and `output_folder`.
- Use `public_title` as the visible generated title.
- Use `topic_base` for internal labels and breadcrumbs.
- Do not rebuild `public_title` from `topic_base` unless no `public_title` exists.
- Use the user request for `focus` and `variant`.
- If the user request omits `focus` or `variant`, use `default_focus` and `default_variant` from the roadmap row.
- If both the user request and roadmap row omit `focus` and `variant`, create a broad overview.
- Keep the lesson inside the selected `focus` and `variant` scope.
- Build the Markdown filename as `output_folder + base_slug + .md`.
- Build the HTML filename by mirroring `base_slug` in `docs/content-system/generated/generated-html-posts/grammar/`.
- Do not print external metadata in the lesson body.
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
- Exercise subsection headings must always use `<h3 class="exercise-title">Seleccionar</h3>`, `<h3 class="exercise-title">Completar</h3>`, and `<h3 class="exercise-title">Traducir</h3>`.
- Use `Completar`, not `Fill in the blank`.
- Add 8 multiple-choice exercises with varied answer positions.
- Add 8 completar exercises.
- Add 8 translation exercises.
- Do not repeat the same sentences or the same subject order across `Seleccionar`, `Completar`, and `Traducir`.
- Vary subject order instead of following the conjugation table from `Yo` to `Ellos`.
- Mix `yo`, `tú`, `él/ella/usted`, `nosotros`, and `ellos/ustedes` across each exercise set.
- Use different sentences in each exercise section.
- Use genuinely different sentence sets for `Seleccionar`, `Completar`, and `Traducir`.
- Mix subjects inside each exercise set.
- Do not follow conjugational order inside any exercise set.
- Avoid repeating exact examples already used in `Overview`, `Conjugation`, `Uses`, or `Oraciones`.
- Do not recycle examples from `Overview`, `Conjugation`, `Uses`, or `Oraciones` unless necessary for didactic control.
- Include affirmative, negative, and question prompts when the structure allows it.
- Keep vocabulary and difficulty aligned with the lesson level.
- In `Oraciones`, `Afirmativa`, `Negativa`, `Preguntas`, and verb examples, keep Spanish and English on the same line as `Spanish - English`.
- Do not put translations under the Spanish with line breaks.
- Do not wrap same-line list translations in `<span class="translation">`; reserve translation cells for tables when needed.
- Every exercise item in `Seleccionar`, `Completar`, and `Traducir` must include a `<details>` answer block.
- Exercise answer toggles must use `<summary>Respuesta</summary>`.
- Do not use the old longer answer label.
- For completar and traducir exercises, put the prompt before the details block, then use the details block only for the answer.
- Use this exercise pattern:
  `<li><p>{{prompt}}</p><details><summary>Respuesta</summary><p>{{answer}}</p></details></li>`

## Regular Verb Tables

- For regular verb tense lessons, do not create a separate `Structure` table when it only repeats endings.
- For common-verb lessons, do not create a separate `Structure` table when it only repeats a pattern, endings, or information already covered by `Conjugation`.
- Move regular endings into each verb table.
- Use separate tables for `Regular -ar Verbs`, `Regular -er Verbs`, and `Regular -ir Verbs` when the lesson teaches all three regular verb groups.
- Each regular verb table must include these columns: `Subject`, `Ending`, the model verb such as `Hablar`, `Comer`, or `Vivir`, `Example`, and `Translation`.
- Do not use a redundant table that only lists verb type, infinitive ending, tense ending, and one example.
- For regular or irregular verbs of common use, use `Conjugation` as the main table.
- For one isolated verb lesson, use one conjugation table with `Subject`, `Form`, `Example`, and `Translation`.

## Conjugation Practice

- Use `sn-conjugation-practice` only for `lesson_type=verb-usage` with one simple isolated verb.
- In generated HTML, `sn-conjugation-practice` must be the real HTML block from `docs/content-system/templates/html-posts/grammar-html-post-template.md`.
- The generated HTML must include:
  - `<div class="sn-conjugation-practice">`
  - `<table class="sn-conj-table">`
  - `<button type="button" class="sn-conj-check">Respuestas</button>`
  - `<button type="button" class="sn-conj-reset">Reiniciar</button>`
- Never generate `sn-conjugation-practice` as plain text.
- Never generate `<p class="sn-conjugation-practice">`.
- Never generate `{{sn_conjugation_practice_marker}}`.
- Do not use `sn-conjugation-practice` in structures, compound phrases, periphrases, or expressions such as `tener que`, `hay algo`, or `he tenido problemas`.

## Uses

- In common-verb lessons, separate the real basic uses of the verb.
- Do not impose a fixed number of uses.
- Repeat the use block pattern only for uses that apply.
- Omit unused use blocks completely.
- For polysemous verbs, separate the main senses.
- For example, `hacer` can separate doing activities, making/preparing things, and weather.

## Wrap Up

- Summarize the central uses taught in the lesson.
- Do not enumerate every detail from the lesson.
- Use natural purpose phrases such as:
  - `To talk about destinations`
  - `To talk about movement`
  - `To talk about plans`
  - `To do an activity`
  - `To make something`
  - `To talk about weather`
- Do not use unnatural metalinguistic phrases such as `Say where I go`.

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
- brief intro
- overview
- conjugation
- conjugation practice when the target is one isolated verb
- uses
- oraciones
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
