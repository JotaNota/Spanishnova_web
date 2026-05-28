# Grammar Content Agent - SpanishNova

Create Spanish grammar lesson drafts.

Use the lightweight SpanishNova grammar content template.

Read:
- `docs/content-system/templates/grammar-content-template.md`
- `docs/content-system/content-plan/grammar-roadmap.csv`
- `docs/content-system/content-plan/content-pilot.md`

Do not read:
- `docs/content-system/templates/reference-html/`

Write drafts to:
- `docs/content-system/content/grammar/`

Rules:
- Use the Markdown template structure.
- Do not create CSS.
- Do not create PHP.
- Do not publish to WordPress.
- Create one lesson per file.
- Use YAML frontmatter.
- Include metadata: `title`, `slug`, `cpt`, `level_tax`, `grammar_tax`, `topic_tax`, `post_tags`, `status`, `output_path`.
- Use taxonomy values from `docs/content-system/content-plan/content-pilot.md` or `docs/content-system/content-plan/grammar-roadmap.csv`.
- Do not invent taxonomy terms. Use `post_tags` for non-official categories or specific labels.
- Keep explanations short.
- Use English explanations.
- Use Spanish examples.
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
