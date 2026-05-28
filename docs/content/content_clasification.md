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
| `topic_tax` | custom hierarchical taxonomy | broad semantic contexts | `vocabulary`, `readings`, `conversations`, `practice`, `resources` |
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

Used for broad semantic contexts.

Architecture rule:

- `topic_tax` uses max 2 levels.

Initial seeded structure:

```txt
Daily Life
- Home
- Routine
- Relationships
- Health
- Hobbies
- Leisure

Travel
Food
Work
Culture
Entertainment
Education
Society
```

Rules:

- Use `topic_tax` for broad semantic contexts.
- Use `topic_tax` across CPTs when the same topic applies to vocabulary, readings, conversations, practice, or resources.
- Do not use `topic_tax` for grammar points such as present, preterite, ser, estar, subjunctive, or imperative.
- Do not create separate `reading_tax` or `vocabulary_tax`.
- Do not create very specific proper nouns as `topic_tax` terms too early. Use native WordPress tags for those.
- Do not turn every future post title into a taxonomy term.
- A taxonomy term should exist when it can group multiple posts.

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
