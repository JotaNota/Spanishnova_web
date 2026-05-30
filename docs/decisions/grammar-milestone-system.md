# Grammar Milestone System

This document records a future editorial system for SpanishNova grammar content.

The milestone system is not implemented yet.

## Purpose

A milestone is a production batch for grammar content.

It helps decide which lessons should be created together and in which teaching moment they belong.

A milestone does not replace the grammar roadmap fields.

It does not replace:

- `grammar_tax`
- `level_tax`
- `lesson_type`
- `default_focus`
- `default_variant`
- `post_tags`

The roadmap remains a content database. Milestone can later become a filterable field inside that database.

## Future use

In a future stage, the grammar roadmap may include a `milestone` column.

That would allow prompts such as:

- create all planned posts for milestone 1
- list remaining posts in milestone 2
- generate the next lesson from `m1-present-core`

The agent must treat milestone as production metadata.

The agent must not use milestone as:

- taxonomy
- topic
- title
- visible lesson content
- WordPress tag
- SEO phrase

## Draft milestone model

### M1 — Present core

Core present indicative lessons and structures.

Possible items:

- `ser`
- `estar`
- `ir`
- `hacer`
- `tener`
- `tener que / deber`
- `poder`
- `querer / preferir / necesitar`
- `gustar / encantar / interesar`

Notes:

- `necesitar` should not be treated as a standalone lesson at this stage.
- It can be grouped with `querer` and `preferir`.
- Action verbs such as `hablar`, `comer`, `vivir`, `ver`, `venir`, and `dar` should appear in examples and exercises, not as isolated beginner grammar lessons.

### M2 — Present progressive

Core progressive structure after present indicative foundations.

Possible items:

- `estar + gerundio`
- present indicative vs present progressive

Notes:

- `seguir + gerundio` may come later.
- `ir + gerundio` may come later.

### M3 — Reflexive core

Core reflexive system.

Possible items:

- reflexive pronouns
- regular reflexive verbs as a group
- reflexive vs non-reflexive meaning
- `hay que`

Notes:

- Verbs such as `llamarse`, `levantarse`, `ducharse`, and `acostarse` should be examples inside the reflexive system, not isolated lessons by default.

### M4 — Past core

Core past tense contrast.

Possible items:

- preterite regular verbs
- preterite irregular core
- imperfect
- preterite vs imperfect

### M5 — Object pronouns

Core direct and indirect object structures.

Possible items:

- direct object pronouns
- indirect object pronouns
- `decir + indirect object`
- `dar + indirect object`
- gustar-type verbs with indirect object

Notes:

- `decir` and `dar` should not be early standalone present indicative lessons.
- They fit better when indirect object pronouns are introduced.

## Naming convention

Possible future milestone values:

- `m1-present-core`
- `m2-present-progressive`
- `m3-reflexive-core`
- `m4-past-core`
- `m5-object-pronouns`

These names are provisional.
