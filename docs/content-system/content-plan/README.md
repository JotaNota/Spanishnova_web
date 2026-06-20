# Content Plan Roadmaps

This folder contains roadmap CSV files for SpanishNova content production.

Each roadmap defines the editorial queue for one content type. Roadmaps do not all use the same schema. Use the schema documented for each file.

## Roadmaps

```text
docs/content-system/content-plan/grammar-roadmap.csv
docs/content-system/content-plan/vocabulary-roadmap.csv
docs/content-system/content-plan/reading-roadmap.csv
docs/content-system/content-plan/conversation-roadmap.csv
```

## Grammar roadmap

File:

```text
grammar-roadmap.csv
```

Schema:

```text
base_slug,topic_base,public_title,status,cpt,lesson_type,level_tax,grammar_tax,topic_tax,route_tax,route_block,route_step,default_focus,default_variant,post_tags,priority,output_folder
```

Columns:

| Column            | Meaning                                                                                  |
| ----------------- | ---------------------------------------------------------------------------------------- |
| `base_slug`       | Stable slug for the item. Also used as the generated file name.                          |
| `topic_base`      | Internal label for the grammar topic or concept.                                         |
| `public_title`    | Visible WordPress post title. Generated grammar HTML does not include an H1.             |
| `status`          | Production state.                                                                        |
| `cpt`             | WordPress custom post type. For this roadmap: `grammar`.                                 |
| `lesson_type`     | Grammar lesson structure. Valid values: `tense`, `verb-usage`, `structure`, `comparison`, `particle-set`. |
| `level_tax`       | Difficulty taxonomy term.                                                                |
| `grammar_tax`     | Grammar taxonomy term.                                                                   |
| `topic_tax`       | Semantic topic taxonomy term. Can be empty for grammar items.                            |
| `route_tax`       | Optional route taxonomy term for the guided route, such as `beginner`, `intermediate`, or `advanced`. Leave empty when the item is not part of a guided route or the assignment is unclear. |
| `route_block`     | Optional display block within the route, such as `01`, `02`, or `03`. This is stored as post meta in WordPress. |
| `route_step`      | Optional ordering value within `route_block`, using multiples of 10. This is stored as post meta in WordPress and is not shown as a visible lesson number. |
| `default_focus`   | Default grammar focus used by the content agent when no focus is specified.              |
| `default_variant` | Optional structure variant. Can be empty.                                                |
| `post_tags`       | Native WordPress tags. Use semicolons for multiple tags.                                 |
| `priority`        | Production order. Lower numbers come first.                                              |
| `output_folder`   | Folder where generated Markdown files are expected.                                      |

### Grammar public title rules

* `public_title` is the visible WordPress post title. Grammar generated HTML is body-only and must not include the title, H1, metadata, navigation, or page layout.
* Use SEO-readable titles, but do not force English grammar categories when they distort the Spanish grammar concept.
* Keep valid learner-search terms when they match Spanish-learning usage, such as `preterite`, `imperfect`, `subjunctive`, `conditional`, `imperative`, `reflexive verbs`, and `object pronouns`.
* Avoid public titles centered on English-only labels such as `present perfect`, `present progressive`, or `simple future`.
* For those cases, prefer functional or Spanish-structure titles, such as:
  * How to Talk About What You Have Done in Spanish
  * How to Use “Haber + Participio” in Spanish
  * How to Talk About What Is Happening Now in Spanish
  * How to Use “Estar + Gerundio” in Spanish
* English search labels may remain in `default_focus` or `post_tags`.

### Grammar lesson types

Grammar content-data JSON is the editable source. Generated Markdown and HTML are outputs.

WordPress owns post title, H1, metadata, navigation, and layout. Grammar generated HTML is clean post-body HTML only.

| `lesson_type`   | Required content-data fields beyond the base fields                       | Notes                                                                 |
| --------------- | ------------------------------------------------------------------------- | --------------------------------------------------------------------- |
| `tense`         | `conjugation`                                                             | Use for verb tense lessons.                                           |
| `verb-usage`    | `conjugation`                                                             | Use for one verb or verb family usage lessons.                        |
| `structure`     | `structure` or `forms`                                                    | Use patterns or forms; do not force conjugation.                      |
| `comparison`    | `comparison` or `structure`                                               | Use comparison rows or pattern rows; do not force conjugation.        |
| `particle-set`  | `forms`; `forms_table` when a table is needed                             | Use for particles or form groups that do not depend on a verb tense.  |

Base grammar fields are `intro`, `overview`, `examples`, `uses`, `sentences`, `exercises`, and `answers`.

## Vocabulary roadmap

File:

```text
vocabulary-roadmap.csv
```

Schema:

```text
base_slug,public_title,vocabulary_variant,status,cpt,level_tax,grammar_tax,topic_tax,post_tags,priority,output_folder
```

Columns:

| Column               | Meaning                                                         |
| -------------------- | --------------------------------------------------------------- |
| `base_slug`          | Stable slug for the item. Also used as the generated file name. |
| `public_title`       | Visible WordPress post title.                                   |
| `vocabulary_variant` | Optional vocabulary structure variant. Can be empty.            |
| `status`             | Production state.                                               |
| `cpt`                | WordPress custom post type. For this roadmap: `vocabulary`.     |
| `level_tax`          | Difficulty taxonomy term.                                       |
| `grammar_tax`        | Grammar taxonomy term. Usually empty for vocabulary items.      |
| `topic_tax`          | Semantic topic taxonomy term.                                   |
| `post_tags`          | Native WordPress tags. Use semicolons for multiple tags.        |
| `priority`           | Production order. Lower numbers come first.                     |
| `output_folder`      | Folder where generated Markdown files are expected.             |

## Reading roadmap

File:

```text
reading-roadmap.csv
```

Schema:

```text
base_slug,public_title,default_variant,status,cpt,level_tax,grammar_tax,topic_tax,post_tags,priority,output_folder
```

Columns:

| Column            | Meaning                                                               |
| ----------------- | --------------------------------------------------------------------- |
| `base_slug`       | Stable slug for the item. Also used as the generated file name.       |
| `public_title`    | Visible WordPress post title.                                         |
| `default_variant` | Reading structure variant used to select the matching reading format. |
| `status`          | Production state.                                                     |
| `cpt`             | WordPress custom post type. For this roadmap: `readings`.             |
| `level_tax`       | Difficulty taxonomy term.                                             |
| `grammar_tax`     | Grammar taxonomy term. Usually empty for reading items.               |
| `topic_tax`       | Semantic topic taxonomy term.                                         |
| `post_tags`       | Native WordPress tags. Use semicolons for multiple tags.              |
| `priority`        | Production order. Lower numbers come first.                           |
| `output_folder`   | Folder where generated Markdown files are expected.                   |

## Conversation roadmap

File:

```text
conversation-roadmap.csv
```

Schema:

```text
base_slug,public_title,default_variant,status,cpt,level_tax,grammar_tax,topic_tax,post_tags,priority,output_folder
```

Columns:

| Column            | Meaning                                                                         |
| ----------------- | ------------------------------------------------------------------------------- |
| `base_slug`       | Stable slug for the item. Also used as the generated file name.                 |
| `public_title`    | Visible WordPress post title.                                                   |
| `default_variant` | Conversation structure variant used to select the matching conversation format. |
| `status`          | Production state.                                                               |
| `cpt`             | WordPress custom post type. For this roadmap: `conversations`.                  |
| `level_tax`       | Difficulty taxonomy term.                                                       |
| `grammar_tax`     | Grammar taxonomy term. Usually empty for conversation items.                    |
| `topic_tax`       | Semantic topic taxonomy term.                                                   |
| `post_tags`       | Native WordPress tags. Use semicolons for multiple tags.                        |
| `priority`        | Production order. Lower numbers come first.                                     |
| `output_folder`   | Folder where generated Markdown files are expected.                             |

## Status values

| Status           | Meaning                                                          |
| ---------------- | ---------------------------------------------------------------- |
| `planned`        | The item exists in the roadmap but has not been generated yet.   |
| `markdown_ready` | Markdown source exists.                                          |
| `html_ready`     | HTML fragment exists and is ready for WordPress import or paste. |
| `published`      | The content has been published.                                  |
| `deleted`        | The generated file was removed after publication.                |

## Notes

* `public_title` is the title intended for the generated post.
* `base_slug` should stay stable once content is generated.
* `topic_tax`, `grammar_tax`, `level_tax`, and `post_tags` should match the editorial model used by WordPress.
* `route_tax` identifies the overall guided route. Use `route_block` for visible route sections and `route_step` for ordering inside each block.
* A post can still belong to a level without belonging to a route.
* `output_folder` points to the expected Markdown output folder, not the HTML output folder.
* Grammar exercise sets should vary sentence content and subject order across `Seleccionar`, `Completar`, and `Traducir`.
* Structured grammar lessons pair the roadmap row with `docs/content-system/content-data/grammar/[base_slug].json` and render with `python scripts/render_content.py --type grammar --slug [base_slug]`.
