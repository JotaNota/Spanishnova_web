# SpanishNova Content Classification

This document is the source of truth for the SpanishNova content model.

SpanishNova uses:

- Custom Post Types to define content type.
- Custom taxonomies to classify content in a controlled way.
- Native WordPress tags for free labels, search, related content, and specific cases.

---

# Custom Post Types

```txt
grammar
vocabulary
readings
conversations
practice
resources
```

CPTs define what the content is.

---

# Official Classification System

SpanishNova uses three custom taxonomies:

```txt
level_tax
grammar_tax
topic_tax
```

SpanishNova also uses native WordPress tags:

```txt
post_tag
```

Important:

- `post_tag` is the native WordPress tag taxonomy.
- Do not register a custom taxonomy named `post_tag`.
- Do not seed `post_tag` terms in the theme.

Deprecated taxonomy names:

```txt
level
topic
vocabulary_tax
reading_tax
```

Do not reintroduce deprecated taxonomy names.

---

# Classification Usage Matrix

| System | Type | Main purpose | Used by |
|---|---|---|---|
| `level_tax` | custom hierarchical taxonomy | learning difficulty | all learning CPTs |
| `grammar_tax` | custom hierarchical taxonomy | grammar navigation and grammar points | `grammar`, optional in `readings`, `practice`, `conversations` |
| `topic_tax` | custom flat taxonomy | broad semantic contexts | `vocabulary`, `readings`, `conversations`, `practice`, `resources` |
| WordPress native tags (`post_tag`) | native non-hierarchical taxonomy | free labels, specific terms, concrete words, contrasts, dialects, expressions, and cross-cutting cases | all CPTs |

---

# level_tax

Used for learning difficulty.

Architecture rule:

- `level_tax` stays flat.

Initial seeded structure:

```txt
beginner
intermediate
advanced
```

Rules:

- `level_tax` is transversal.
- It can be assigned to any learning CPT.
- It should not be used for topics.
- It should not be used for grammar points.
- It should not be replaced with native WordPress tags.

---

# grammar_tax

Used for grammar navigation and grammar points.

Architecture rule:

- `grammar_tax` may use 3 levels.

Initial seeded structure:

```txt
Tenses
- Present
- Past
  - Preterite
  - Imperfect
- Future
- Conditional
- Progressive
- Perfect Tenses

Verbs
- Ser
- Estar
- Tener
- Ir
- Haber
- Gustar-Type Verbs
- Reflexive Verbs
- Irregular Verbs

Moods
- Indicative
- Subjunctive
- Imperative

Parts of Speech
- Articles
- Nouns
- Adjectives
- Pronouns
- Adverbs
- Prepositions
- Conjunctions

Sentence Structure
- Questions
- Negation
- Comparisons
- Word Order
- Direct and Indirect Speech
```

Rules:

- Use `grammar_tax` when the term is a grammar point.
- Use `grammar_tax` for grammar navigation.
- Do not use `grammar_tax` for broad semantic contexts such as airport, food, family, culture, or entertainment.
- Do not use native WordPress tags as the primary place for grammar navigation.
- It is valid to assign multiple `grammar_tax` terms to the same post.
- Do not turn every future post title into a taxonomy term.
- A taxonomy term should exist when it can group multiple posts.
- Do not seed post-level terms such as `Regular Preterite`, `Irregular Preterite`, `Regular Reflexive Verbs`, or `Irregular Reflexive Verbs`.

---

# topic_tax

Used for broad subject areas shared by Vocabulary, Readings, Conversations, Practice, and Resources.

Architecture rule:

- `topic_tax` uses one flat level for the editorial catalog.
- Do not add subcategories for individual lessons or readings.
- Use native tags for specific subjects, people, animals, and places.
- The previous `Daily Life` child terms may still exist in installed WordPress databases. Do not delete or reassign them without auditing content.

Seeded structure:

```txt
Daily Life
Travel
Food
Work
Education
Culture
Entertainment
Society
Science & Nature
```

Definitions and examples:

| Topic | Scope |
|---|---|
| Daily Life | Everyday situations: home, routines, shopping, errands, personal interactions |
| Travel | Trips, transport, accommodation, destinations |
| Food | Food, cooking, meals, restaurants |
| Work | Jobs, workplaces, professional activities |
| Education | Schools, study, teaching, learning |
| Culture | Traditions, arts, cultural practices, cultural heritage |
| Entertainment | Film, music, games, leisure media |
| Society | Social organization, communities, institutions, social issues |
| Science & Nature | Animals, ecology, natural phenomena, scientific discoveries |

Examples of reading classification:

```txt
Sharks       -> Science & Nature | tags: sharks, marine life
Navajo       -> Culture          | tags: Navajo, Indigenous peoples
Hedy Lamarr  -> Culture          | tags: Hedy Lamarr, cinema, technology
```

Choose the topic from the actual focus of the reading. For example, Hedy Lamarr may belong to Science & Nature if the article focuses on technology rather than biography or film. A reading may use more than one topic when justified.

Rules:

- `Daily Life` is only for everyday situations. Never use it as a fallback for a reading without an obvious topic.
- `Work` and `Education` are separate.
- `Culture` and `Society` are separate. Use `Society` for social organization or institutions, rather than treating it as a catch-all category.
- Do not use `topic_tax` for grammar points; use `grammar_tax`.
- Keep specific terms, proper names, and narrow topics in WordPress tags.
- Do not create `reading_tax` or `vocabulary_tax`.
- A new topic should be introduced only when it groups multiple items.

---

# Native WordPress Tags

Use native WordPress tags: `post_tag`.

These are regular WordPress tags.

Do not create a custom taxonomy for tags.

Do not seed `post_tag` terms in the theme.

Tags are free labels. They do not need a fixed hierarchy.

Use `post_tag` for:

- free labels
- specific terms
- concrete words
- contrasts
- dialects
- expressions
- cross-cutting cases
- specific entities
- variants
- loose keywords

Examples:

```txt
regular preterite
irregular preterite
regular reflexive verbs
irregular reflexive verbs
airport phrases
common verbs
Machu Picchu
Peru
tourism
cinema
plans
free time
```

Rules:

- Do not register a custom taxonomy named `post_tag`.
- Do not use tags as the main navigation.
- Do not use tags to replace `topic_tax`.
- Do not use tags to replace `grammar_tax`.
- Tags can be assigned freely when they help search, filtering, or related content.

---

# Classification Decision Rules

## Grammar points go to `grammar_tax`

Use `grammar_tax` when the term is a grammar point someone may search as a lesson or use for grammar navigation.

Examples:

```txt
present
preterite
imperfect
future
subjunctive
imperative
ser
estar
haber
pronouns
questions
negation
```

## Semantic contexts go to `topic_tax`

Use `topic_tax` when the term describes what the content is broadly about.

Examples:

```txt
home
routine
relationships
health
hobbies
leisure
travel
food
work
culture
entertainment
education
society
science and nature
```

## Specific cases go to native WordPress tags

Use native WordPress tags for specific cases, loose labels, entities, variations, dialects, and expressions.

Examples:

```txt
regular preterite
irregular preterite
regular reflexive verbs
irregular reflexive verbs
airport phrases
movie night
Machu Picchu
Peru
```

---

# Navigation Logic

The frontend navigation should primarily use:

```txt
grammar_tax
topic_tax
level_tax
```

The frontend should not use native WordPress tags as the main navigation system.

Recommended navigation model:

```txt
Grammar
-> grammar_tax

Vocabulary
-> topic_tax filtered by CPT vocabulary

Readings
-> topic_tax filtered by CPT readings

Conversations
-> topic_tax filtered by CPT conversations

Practice
-> grammar_tax and/or topic_tax depending on the exercise type

Resources
-> topic_tax
```

---

# Main Rule

Do not mix these dimensions:

```txt
CPT                            = content type
level_tax                      = difficulty
grammar_tax                    = grammar point
topic_tax                      = broad semantic context
WordPress native tags/post_tag = loose search labels and specific cases
```
