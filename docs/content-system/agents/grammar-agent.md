# Grammar Content Agent - SpanishNova

Create structured Spanish grammar lesson content-data and render generated review/output files.

## Read

- `docs/content-system/content-plan/grammar-roadmap.csv`
- `docs/content-system/schemas/grammar-content-data.schema.json`
- existing examples in `docs/content-system/content-data/grammar/` when they help with structure or tone
- `scripts/render_content.py` and `scripts/spanishnova_render/` only to understand current render behavior

## Official Flow

1. Read `docs/content-system/content-plan/grammar-roadmap.csv`.
2. Identify the target row by `base_slug`, `topic_base`, or `public_title`.
3. Read `docs/content-system/schemas/grammar-content-data.schema.json`.
4. Create or edit `docs/content-system/content-data/grammar/[slug].json`.
5. Run `python scripts/render_content.py --type grammar --slug [slug]`.
6. Confirm the render generated:
   - `docs/content-system/generated/generated-markdown-posts/grammar/[slug].md`
   - `docs/content-system/generated/generated-html-posts/grammar/[slug].html`
7. Review `git status --short`.

The editable source of a grammar lesson is the content-data JSON. Markdown and HTML are generated outputs.

## Content Data

- Follow `docs/content-system/schemas/grammar-content-data.schema.json`.
- Standard grammar lessons use `intro`, `overview`, `examples`, `conjugation`, `uses`, `sentences`, `exercises`, and `answers`.
- `particle-set` lessons use `intro`, `overview`, `examples`, `forms`, `uses`, `sentences`, `exercises`, and `answers`.
- Keep content corrections in JSON and rerender.
- Use `TBD` only for unknown content that needs human review.

## Clean HTML Contract

Generated grammar HTML is post-body HTML only. WordPress owns the post title, metadata, navigation trail, and page layout.

Do not generate:

- post titles or H1 tags
- navigation-trail markup
- metadata rows
- taxonomy pills
- card or panel wrappers
- document wrappers such as `html`, `head`, or `body`
- Gutenberg comments or `wp:` blocks
- CSS, JS, or PHP

Use simple sections only:

- `intro`
- `Overview`
- `Formas`, only when forms apply
- `Conjugación`, only when conjugation applies
- `Cómo/cuándo lo usamos`
- `Comparación`, only when comparison applies
- `Oraciones.`
- `Ejercicios`
- `Wrap Up`

Examples must stay on one line:

`Español -> English`

## Metadata Flow

- Use the roadmap row for external metadata and generation defaults: `cpt`, `lesson_type`, `level_tax`, `grammar_tax`, `topic_tax`, `default_focus`, `default_variant`, `post_tags`, and `output_folder`.
- Use `public_title` only as WordPress post metadata.
- Use `topic_base` only for roadmap matching and taxonomy context.
- Do not print external metadata in the lesson body.
- Do not invent taxonomy terms.

## Practice Structure

- Use `Afirmativas`, `Negativas`, and `Forma de pregunta` under `Oraciones.`.
- Use only `select`, `complete`, and `translate` exercise groups.
- Use `Selección simple`, `Completar`, and `Traducción` as exercise subsection headings.
- Every exercise item must include a `<details><summary>Respuesta</summary>...</details>` answer block.
- Selection exercises are allowed but not required for every grammar lesson.
- Avoid repeating exact examples already used in `Overview`, `Conjugación`, `Cómo/cuándo lo usamos`, or `Oraciones.`.
- Keep vocabulary and difficulty aligned with the roadmap level.

## Tables

- Use `Forma`, not an English form-column label, in conjugation tables.
- Use `Patrón`, `Example`, and `Translation` for comparison or pattern tables.
- Use `class="translation"` only for true translation cells.
- Do not use translation styling on Spanish examples, forms, endings, or plural columns.

## Conjugation Practice

- Use `sn-conjugation-practice` only for `lesson_type=verb-usage` with one simple isolated verb.
- Do not use it for structures, compound phrases, periphrases, or expressions.

## Lesson Types

- `tense`
- `verb-usage`
- `structure`
- `comparison`
- `particle-set`

For `tense`, use overview, conjugation, usage, sentences, exercises, and wrap up.

For `verb-usage`, use overview, conjugation, usage, examples, exercises, and wrap up.

For `structure`, use overview, forms or patterns, usage, sentences, exercises, and wrap up.

For `comparison`, use overview, usage, comparison, sentences, exercises, and wrap up.

For `particle-set`, use overview, forms, usage, sentences, exercises, and wrap up.

## Language Quality

- Keep explanations short.
- Use English explanations.
- Use Spanish examples.
- Use English translations.
- Spanish examples, questions, exercises, and titles must use correct accents and Spanish punctuation.
