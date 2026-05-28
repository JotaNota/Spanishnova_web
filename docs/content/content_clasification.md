# SpanishNova Content Classification

This document is the source of truth for the SpanishNova content model.

SpanishNova uses:

- Custom Post Types to define content type.
- Custom taxonomies to classify content in a controlled way.
- Native WordPress tags for free labels, search, and related content.

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

Examples:

```txt
grammar       → grammar lesson
vocabulary    → vocabulary lesson
readings      → reading content
conversations → dialogue or conversation content
practice      → exercise or practice item
resources     → downloadable or support resource
```

---

# Official classification system

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

`post_tag` is the native WordPress tag taxonomy. Do not register a new custom taxonomy named `post_tag`.

Do not use these deprecated taxonomy names:

```txt
level
topic
vocabulary_tax
reading_tax
```

---

# Classification usage matrix

| System | Type | Main purpose | Used by |
|---|---|---|---|
| `level_tax` | custom hierarchical taxonomy | learning difficulty | all learning CPTs |
| `grammar_tax` | custom hierarchical taxonomy | grammar points and grammar navigation | `grammar`, optional in `readings`, `practice`, `conversations` |
| `topic_tax` | custom hierarchical taxonomy | semantic topics | `vocabulary`, `readings`, `conversations`, `practice`, `resources` |
| WordPress native tags (`post_tag`) | native non-hierarchical taxonomy | free labels, search, related content | all CPTs |

---

# level_tax

Used for learning difficulty.

Recommended initial structure:

```txt
beginner
intermediate
advanced
```

Possible future structure:

```txt
A1
A2
B1
B2
C1
```

Rules:

- `level_tax` is transversal.
- It can be assigned to any learning CPT.
- It should not be used for topics.
- It should not be used for grammar points.
- It should not be replaced with native WordPress tags.

Examples:

```txt
Futuro simple
→ CPT: grammar
→ level_tax: beginner

Airport interview
→ CPT: readings
→ level_tax: beginner
```

---

# grammar_tax

Used to organize grammar lessons and grammar points.

Supports hierarchy.

`grammar_tax` is not a general topic taxonomy. It is only for grammar.

A single post can use more than one `grammar_tax` term.

Example:

```txt
Tener que + presente
→ grammar_tax: Verbs > Tener que
→ grammar_tax: Tenses > Present
```

Initial structure:

```txt
Tenses
├─ Present
├─ Past
├─ Future
├─ Conditional
├─ Progressive Tenses
└─ Perfect Tenses

Moods
├─ Subjunctive
└─ Imperative

Verbs
├─ Ser
├─ Estar
├─ Tener
├─ Ir
├─ Querer
├─ Necesitar
├─ Gustar
├─ Tener que
├─ Ir a + Infinitive
└─ Reflexive Verbs

Parts of Speech
├─ Pronouns
├─ Adjectives
├─ Adverbs
├─ Prepositions
├─ Connectors
└─ Articles
```

Examples:

```txt
Presente
→ CPT: grammar
→ grammar_tax: Tenses > Present

Pretérito
→ CPT: grammar
→ grammar_tax: Tenses > Past

Ser
→ CPT: grammar
→ grammar_tax: Verbs > Ser

Tener que
→ CPT: grammar
→ grammar_tax: Verbs > Tener que

Tener que + presente
→ CPT: grammar
→ grammar_tax: Verbs > Tener que
→ grammar_tax: Tenses > Present

Ir a + infinitive
→ CPT: grammar
→ grammar_tax: Verbs > Ir a + Infinitive

Subjuntivo imperfecto
→ CPT: grammar
→ grammar_tax: Moods > Subjunctive

Imperativo irregular
→ CPT: grammar
→ grammar_tax: Moods > Imperative

Adjetivos posesivos
→ CPT: grammar
→ grammar_tax: Parts of Speech > Adjectives
```

Cross-use examples:

```txt
Airport interview
→ CPT: readings
→ grammar_tax: Tenses > Present
→ topic_tax: Travel > Airport

Practice with tener que
→ CPT: practice
→ grammar_tax: Verbs > Tener que
→ grammar_tax: Tenses > Present
```

Rules:

- Use `grammar_tax` when the term is a grammar point.
- Do not use `grammar_tax` for semantic topics such as airport, food, family, cinema, or Machu Picchu.
- Do not use native WordPress tags as the primary place for grammar navigation.
- It is valid to assign multiple `grammar_tax` terms to the same post.

---

# topic_tax

Used to organize content by semantic topic.

Supports hierarchy.

`topic_tax` replaces the older planned taxonomies:

```txt
vocabulary_tax
reading_tax
```

Those taxonomies should not be used.

`topic_tax` can be used across multiple CPTs.

Initial structure:

```txt
Daily Life
├─ Home
├─ Sleep
├─ Family
├─ Routine
└─ Entertainment

Travel
├─ Hotel
├─ Airport
├─ Taxi
└─ Vacation

Work
├─ Office
├─ Meetings
├─ Jobs
└─ Business

Food
├─ Restaurant
├─ Supermarket
├─ Café
└─ Cooking

Culture
├─ Traditions
├─ Cities
└─ Latin America

Education
├─ History
├─ Science
├─ Technology
└─ Famous People
```

Examples:

```txt
Airport
→ topic_tax: Travel > Airport

Hotel room
→ CPT: vocabulary
→ topic_tax: Travel > Hotel

At the café
→ CPT: vocabulary
→ topic_tax: Food > Café

Airport interview
→ CPT: readings
→ topic_tax: Travel > Airport

Restaurant dialogue
→ CPT: conversations
→ topic_tax: Food > Restaurant

Ir al cine
→ CPT: readings or vocabulary
→ topic_tax: Daily Life > Entertainment
```

Purpose:

- topic navigation
- lesson grouping
- SEO structure
- archive filtering
- related content
- content discovery

Rules:

- Use `topic_tax` for broad semantic areas.
- Use `topic_tax` across CPTs when the same topic applies to vocabulary, readings, conversations, practice, or resources.
- Do not use `topic_tax` for grammar points such as present, preterite, tener que, ser, estar, or subjunctive.
- Do not create separate `reading_tax` or `vocabulary_tax` unless the content model is formally changed later.
- Do not create very specific proper nouns as `topic_tax` terms too early. Use native WordPress tags for those.

---

# Native WordPress tags

Use native WordPress tags: `post_tag`.

These are regular WordPress tags.

Do not create a custom taxonomy for tags.

Tags are free labels.

They do not need a fixed hierarchy.

They can be broad, specific, temporary, or content-specific.

Examples:

```txt
restaurant
airport
common phrases
travel
food
doctor
family
shopping
preterite
future tense
regular verbs
conjugation
cinema
plans
free time
Machu Picchu
Peru
tourism
```

Example:

```txt
Post:
Futuro simple

CPT:
grammar

level_tax:
beginner

grammar_tax:
Tenses > Future

WordPress native tags (`post_tag`):
future tense, regular verbs, conjugation
```

Example:

```txt
Post:
Reading about Machu Picchu

CPT:
readings

topic_tax:
Culture > Latin America

WordPress native tags (`post_tag`):
Machu Picchu, Peru, tourism
```

Rules:

- Do not register a new custom taxonomy named `post_tag`.
- Do not use tags as the main navigation.
- Do not use tags to replace `topic_tax`.
- Do not use tags to replace `grammar_tax`.
- Tags can be assigned freely when they help search, filtering, or related content.
- Tags are the right place for specific entities, names, variants, and loose keywords.

---

# Classification decision rules

Use these rules when assigning taxonomy terms.

## 1. Grammar points go to `grammar_tax`

Use `grammar_tax` when the term is a grammar point someone may search as a lesson.

Examples:

```txt
present
preterite
future
subjunctive
imperative
tener que
hay
ser
estar
gustar
comparatives
pronouns
adjectives
```

Correct classification:

```txt
presente
→ grammar_tax: Tenses > Present

pretérito
→ grammar_tax: Tenses > Past

tener que
→ grammar_tax: Verbs > Tener que

ser
→ grammar_tax: Verbs > Ser
```

Do not classify these as `topic_tax`.

Do not rely only on tags for these terms.

---

## 2. Semantic content areas go to `topic_tax`

Use `topic_tax` when the term describes what the content is about.

Examples:

```txt
airport
restaurant
family
hotel
work
shopping
cinema
daily routine
Latin America
```

Correct classification:

```txt
aeropuerto
→ topic_tax: Travel > Airport

restaurante
→ topic_tax: Food > Restaurant

familia
→ topic_tax: Daily Life > Family

ir al cine
→ topic_tax: Daily Life > Entertainment
```

Use `topic_tax` for broad topic navigation and archive filtering.

---

## 3. Specific entities and loose keywords go to native WordPress tags

Use native WordPress tags (`post_tag`) for loose labels, entities, variations, and search helpers.

Important:

`post_tag` is the native WordPress tag taxonomy. Do not register a new custom taxonomy named `post_tag`.

Examples:

```txt
Machu Picchu
Peru
tourism
movie night
airport phrases
common verbs
regular verbs
irregular verbs
```

Correct classification:

```txt
machu pichu
→ WordPress native tags (`post_tag`): Machu Picchu, Peru, tourism
```

Tags can overlap with taxonomy terms when useful for search.

---

## 4. When a term can fit more than one system

Some terms can appear in more than one place, depending on the content.

Example:

```txt
presente
```

If the content teaches the present tense:

```txt
grammar_tax:
Tenses > Present
```

If the phrase is only a loose search keyword:

```txt
WordPress native tags (`post_tag`):
present tense
```

Example:

```txt
tener que + presente
```

If the content teaches both the verb phrase and the tense:

```txt
grammar_tax:
Verbs > Tener que
Tenses > Present
```

Example:

```txt
ir al cine
```

If the content is about leisure, plans, or going to the movies:

```txt
topic_tax:
Daily Life > Entertainment

WordPress native tags (`post_tag`):
cinema, plans, free time
```

If the content teaches a grammar point with `ir`:

```txt
grammar_tax:
Verbs > Ir

topic_tax:
Daily Life > Entertainment
```

Example:

```txt
Machu Picchu
```

If the content is a cultural reading:

```txt
topic_tax:
Culture > Latin America

WordPress native tags (`post_tag`):
Machu Picchu, Peru, tourism
```

Do not create new taxonomy terms for every proper noun too early.

---

# Practical classification examples

```txt
"presente"
→ grammar_tax: Tenses > Present

"tener que" + "presente"
→ grammar_tax: Verbs > Tener que
→ grammar_tax: Tenses > Present

"pretérito"
→ grammar_tax: Tenses > Past

"aeropuerto"
→ topic_tax: Travel > Airport

"ir al cine"
→ topic_tax: Daily Life > Entertainment
→ WordPress native tags (`post_tag`): cinema, plans, free time

"machu pichu"
→ WordPress native tags (`post_tag`): Machu Picchu, Peru, tourism
```

---

# Navigation logic

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
→ grammar_tax

Vocabulary
→ topic_tax filtered by CPT vocabulary

Readings
→ topic_tax filtered by CPT readings

Conversations
→ topic_tax filtered by CPT conversations

Practice
→ grammar_tax and/or topic_tax depending on the exercise type

Resources
→ topic_tax
```

---

# Main rule

Do not mix these dimensions:

```txt
CPT                            = content type
level_tax                      = difficulty
grammar_tax                    = grammar point
topic_tax                      = semantic topic
WordPress native tags/post_tag = loose search labels
```

Example:

```txt
Airport interview

CPT:
readings

level_tax:
beginner

topic_tax:
Travel > Airport

grammar_tax:
Tenses > Present

WordPress native tags (`post_tag`):
airport, travel, common phrases
```