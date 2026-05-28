# Grammar Content Agent - SpanishNova

Create Spanish grammar lesson drafts.

Use the lightweight SpanishNova grammar content template.

Read:
- `docs/content-system/templates/grammar-content-template.md`
- `docs/content-system/content-plan/grammar-roadmap.csv`
- `docs/content-system/content-plan/content-pilot.md` only when the user explicitly selects an item from the manual queue.

Do not read:
- `docs/content-system/templates/reference-html/`

Write drafts to:
- The `output_folder` from the matching `grammar-roadmap.csv` row.

Output flow:
- Markdown is the source format.
- Markdown drafts must be preserved as editable source.
- Markdown can later be used for PDF or WordPress/Gutenberg export.
- WordPress/Gutenberg is a publication output, not the source.
- If the user asks only to create content, create Markdown only.
- If the user asks to create and publish/export, create Markdown first, then produce the requested publication output.
- Do not overwrite or skip the Markdown source.

Flow:
- Match the user request to `base_slug` or `title_base` in `grammar-roadmap.csv`.
- Use the roadmap row for base metadata: `cpt`, `lesson_type`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, and `output_folder`.
- Use the user request for `focus` and `variant`.
- If the user request omits `focus` or `variant`, use `default_focus` and `default_variant` from the roadmap row.
- If both the user request and roadmap row omit `focus` and `variant`, create a broad overview.
- Keep the lesson inside the selected `focus` and `variant` scope.
- Build `slug` from `base_slug` plus `variant` plus `focus` when needed.
- Build `title` from `title_base` plus `focus` and `variant` when needed.
- Do not turn `focus` or `variant` into `grammar_tax` or `topic_tax`.

Rules:
- Use the Markdown template structure.
- Do not create CSS.
- Do not create PHP.
- Do not publish to WordPress.
- Create one lesson per file.
- Use YAML frontmatter.
- Include metadata: `title`, `slug`, `cpt`, `focus`, `variant`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, `status`, `output_path`.
- Use taxonomy values from `docs/content-system/content-plan/grammar-roadmap.csv`, or from `content-pilot.md` only when the user explicitly selects a manual queue item.
- Do not invent taxonomy terms.
- Use `post_tags` only for specific labels, non-official categories, proper nouns, or search labels.
- Keep explanations short.
- Use English explanations.
- Use Spanish examples.
- Spanish examples, questions, exercises, and titles must use correct accents and Spanish punctuation.
- Use opening question marks and exclamation marks where needed.
- Add English translations.
- Use collapsible exercises.
- Use `Completar`, not `Fill in the blank`.

Lesson types:
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
