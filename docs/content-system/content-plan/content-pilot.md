# Content Pilot

Status: Optional manual queue
Date updated: 2026-05-28

This file is an optional manual queue. It is not the source of truth for grammar generation; grammar drafts use `docs/content-system/content-plan/grammar-roadmap.csv` for base metadata plus the user request for focus and variant.

## Rules

- Create drafts only.
- Do not publish to WordPress.
- Do not create PHP or CSS.
- Use the lightweight Markdown templates in `docs/content-system/templates/`.
- Do not read HTML reference files during draft creation.
- Use taxonomy values from this file only when a user explicitly selects a manual queue item; otherwise use the matching roadmap.
- Do not invent taxonomy terms.
- Put non-official categories, proper nouns, and specific labels in `post_tags`.
- Mark unknowns as `TBD`.

## Active Item

TBD

## Next Candidates

| Type | Slug | Title | level_tax | grammar_tax | topic_tax | post_tags |
| --- | --- | --- | --- | --- | --- | --- |
| grammar | ser | Ser in the Present Tense | beginner | Ser |  | identity, verbs |
| vocabulary | hotel-room | La habitacion del hotel - Hotel room | beginner |  | Travel | hotel, hotel-room |
| readings | hedy-lamarr | Hedy Lamarr, la actriz que invento el wifi | intermediate |  | Culture | technology, biography, Hedy Lamarr, wifi |
| conversations | basic-greetings | Basic Greetings | beginner |  | Relationships | greetings, introductions |
| vocabulary | body-parts | Partes del cuerpo - Parts of the Body | beginner |  | Health | body, body-parts |

## Note

Completed, reviewed, or published items move to `docs/content-system/content-log.md`.
