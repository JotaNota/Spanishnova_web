# SpanishNova Content Classification

## Custom Post Types

```txt
grammar
vocabulary
readings
conversations
practice
resources
```

---

# Taxonomies

## level_tax

Used for learning difficulty.

```txt
beginner
intermediate
advanced
```

---

## grammar_tax

Used to organize grammar lessons visually and structurally.

Supports hierarchy.

```txt
Tenses
├─ Present
├─ Past
├─ Future
├─ Conditional
├─ progressive tenses
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
└─ Reflexive Verbs

Parts of Speech
├─ Pronouns
├─ Adjectives
├─ Adverbs
├─ Prepositions
├─ Connectors
└─ Articles

Structures
├─ Hay
├─ Hay + Object
├─ Tener que
├─ Ir a + Infinitive
├─ Comparatives
└─ Tan vs Tanto
```

Examples:

```txt
Ser
→ grammar_tax: Verbs > Ser

Futuro simple
→ grammar_tax: Tenses > Future

Pretérito simple
→ grammar_tax: Tenses > Past

Subjuntivo imperfecto
→ grammar_tax: Moods > Subjunctive

Imperativo irregular
→ grammar_tax: Moods > Imperative

Adjetivos posesivos
→ grammar_tax: Parts of Speech > Adjectives

Hay + algo
→ grammar_tax: Structures > Hay + Object

Tener que
→ grammar_tax: Structures > Tener que
```

---

## vocabulary_tax

Used to organize vocabulary lessons.

Supports hierarchy.

Example structure:

```txt
Daily Life
├─ Home
├─ Sleep
├─ Family
└─ Routine

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
```

Examples:

```txt
Hotel room
→ vocabulary_tax: Travel > Hotel

At the café
→ vocabulary_tax: Food > Café
```

Purpose:

- topic navigation
- lesson grouping
- SEO structure
- archive filtering

---

## reading_tax

Used to organize reading content.

Supports hierarchy.

Example structure:

```txt
Stories
├─ Daily Life
├─ Travel
├─ Funny Stories
└─ Fiction

Culture
├─ Traditions
├─ Food
├─ Cities
└─ Latin America

Educational
├─ History
├─ Science
├─ Technology
└─ Famous People

Interactive
├─ Fake Interviews
├─ Dialogues
├─ Opinions
└─ Situations
```

Examples:

```txt
Hedy Lamarr
→ reading_tax: Educational > Technology

Fake airport interview
→ reading_tax: Interactive > Fake Interviews
```

Purpose:

- reading navigation
- archive organization
- reading difficulty grouping
- content discovery

---

## tags

Tags are lightweight searchable labels.

Tags are NOT part of the main visual navigation.

Tags are used for:

- search
- related content
- keyword matching
- internal search relevance

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
Verbs > Future

tags:
future tense, regular verbs, conjugation
```

---

# Navigation Logic

The frontend navigation should primarily use:

```txt
grammar_tax
vocabulary_tax
reading_tax
```

NOT tags.

This allows:

- structured browsing
- expandable sections
- SEO topic clusters
- filtered archives
- cleaner navigation UX