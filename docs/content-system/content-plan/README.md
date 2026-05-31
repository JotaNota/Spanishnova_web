# Content Plan Roadmaps

This folder contains roadmap CSV files for SpanishNova content production.

All active roadmaps use the same column order. `grammar-roadmap.csv` is the reference schema currently being followed.

```text
base_slug,topic_base,public_title,status,cpt,lesson_type,level_tax,grammar_tax,topic_tax,default_focus,default_variant,post_tags,priority,output_folder
```

## Shared columns

| Column            | Meaning                                                                                 |
| ----------------- | --------------------------------------------------------------------------------------- |
| `base_slug`       | Stable slug for the item. Also used as the generated file name.                         |
| `topic_base`      | Short internal label for the topic or concept.                                          |
| `public_title`    | Publishable title for the generated post.                                               |
| `status`          | Production state: `planned`, `markdown_ready`, `html_ready`, `published`, or `deleted`. |
| `cpt`             | WordPress custom post type.                                                             |
| `lesson_type`     | Content structure used to build the item.                                               |
| `level_tax`       | Difficulty taxonomy term.                                                               |
| `grammar_tax`     | Grammar taxonomy term when applicable. Can be empty.                                    |
| `topic_tax`       | Semantic topic taxonomy term when applicable. Can be empty.                             |
| `default_focus`   | Main focus used when no focus is specified.                                             |
| `default_variant` | Optional variant inside the content structure. Can be empty. To be reviewed later.      |
| `post_tags`       | Native WordPress tags. Use semicolons for multiple tags.                                |
| `priority`        | Production order. Lower numbers come first.                                             |
| `output_folder`   | Folder where generated files are expected.                                              |

## Grammar roadmap

`grammar-roadmap.csv` usually uses `cpt=grammar`. Its `lesson_type` values include `tense`, `verb-usage`, `comparison`, and `structure`. `grammar_tax` is usually important, while `topic_tax` can be empty. Use `public_title` for the generated post title and `topic_base` for breadcrumbs and internal labels.

## Vocabulary roadmap

`vocabulary-roadmap.csv` uses `cpt=vocabulary` and `lesson_type=vocabulary`. `grammar_tax` is usually empty, while `topic_tax` is usually important. Use `public_title` for the publishable vocabulary title and `topic_base` for the internal topic label.

## Reading roadmap

`reading-roadmap.csv` uses `cpt=readings` and `lesson_type=reading`. Use `default_variant` to select the matching reading structure, `public_title` as the generated title, and `base_slug` to build the generated filename. `output_folder` stores the generated Markdown folder.

## Conversation roadmap

`conversation-roadmap.csv` uses `cpt=conversations` and `lesson_type=conversation`. Use `default_variant` to select the matching conversation structure, `public_title` as the generated title, and `base_slug` to build the generated filename. `output_folder` stores the generated Markdown folder.

## Status values

| Status           | Meaning                                                          |
| ---------------- | ---------------------------------------------------------------- |
| `planned`        | The item exists in the roadmap but has not been generated yet.   |
| `markdown_ready` | Markdown source exists.                                          |
| `html_ready`     | HTML fragment exists and is ready for WordPress import or paste. |
| `published`      | The content has been published.                                  |
| `deleted`        | The generated file was removed after publication.                |
```
