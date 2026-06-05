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
base_slug,topic_base,public_title,status,cpt,lesson_type,level_tax,grammar_tax,topic_tax,default_focus,default_variant,post_tags,priority,output_folder
```

Columns:

| Column            | Meaning                                                                                  |
| ----------------- | ---------------------------------------------------------------------------------------- |
| `base_slug`       | Stable slug for the item. Also used as the generated file name.                          |
| `topic_base`      | Internal label for the grammar topic or concept.                                         |
| `public_title`    | Visible post title and generated H1.                                                     |
| `status`          | Production state.                                                                        |
| `cpt`             | WordPress custom post type. For this roadmap: `grammar`.                                 |
| `lesson_type`     | Grammar lesson structure. Current values include `tense`, `verb-usage`, and `structure`. |
| `level_tax`       | Difficulty taxonomy term.                                                                |
| `grammar_tax`     | Grammar taxonomy term.                                                                   |
| `topic_tax`       | Semantic topic taxonomy term. Can be empty for grammar items.                            |
| `default_focus`   | Default grammar focus used by the content agent when no focus is specified.              |
| `default_variant` | Optional structure variant. Can be empty.                                                |
| `post_tags`       | Native WordPress tags. Use semicolons for multiple tags.                                 |
| `priority`        | Production order. Lower numbers come first.                                              |
| `output_folder`   | Folder where generated Markdown files are expected.                                      |

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
| `public_title`       | Visible post title and generated H1.                            |
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
| `public_title`    | Visible post title and generated H1.                                  |
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
| `public_title`    | Visible post title and generated H1.                                            |
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
* `output_folder` points to the expected Markdown output folder, not the HTML output folder.
* Grammar exercise sets should vary sentence content and subject order across `Seleccionar`, `Completar`, and `Traducir`.
