# SpanishNova · Learning routes: A1 Starter / A2 Beginner

Status: editorial working draft (2026-11-21). This document records the current agreed route structure. It does not imply that every named post exists or is published.

## Criteria

- Hierarchy: Learning Route → level (A1 Starter / A2 Beginner) → Module → numbered Lesson → optional associated content.
- Lessons define the numbered sequence within each module; associated Grammar, Vocabulary, Practice or Conversation content is nested under a lesson and has no lesson number.
- Associated content can be added later without renumbering existing lessons.
- A lesson or associated activity can link to an existing post, regardless of its content type. Not every published post must belong to a route.
- The route is an editorial selection, not the entire taxonomy archive. Titles and example activities are tentative until matched to actual posts.
- Keep A1 focused on initial sentence building and common present-time communication. A2 develops present tense, routines and everyday communication. Do not introduce the reserved topics below without a separate editorial decision.
- The currently implemented PHP template only queries `grammar` and uses `route_block` / `route_step`. The structure here is a content plan; it is **not** a claim that nested items are already supported by the theme or WordPress.
- Do not change taxonomies, PHP, CSS, upload scripts, CSV schemas or existing route mappings as a side effect of this document.

## A1 · Starter

### Module 1: First sentences
- Lesson 1: Basic nouns [Vocabulary]
  - Articles [Grammar]
- Lesson 2: Adjectives [Grammar]
  - Physical descriptions [Vocabulary]
- Lesson 3: SER [Grammar]
  - Describe people [Practice]
  - Professions [Vocabulary]
- Lesson 4: ESTAR [Grammar]
  - Feelings and states [Vocabulary]
- Lesson 5: TENER — Descriptions [Grammar]
  - Describe someone [Conversation]
  - Describe who someone is and where they are [Practice]

### Module 2: Actions in progress
- Lesson 1: ESTAR + gerund [Grammar]
- Lesson 2: What are you doing? [Conversation]
- Lesson 3: Present vs. progressive [Practice] — placement pending introduction of regular present tense

### Module 3: Possession
- Lesson 1: TENER — Possession [Grammar]
- Lesson 2: Possessive adjectives [Grammar]
  - Family members [Vocabulary]

### Module 4: Pointing and describing places
- Lesson 1: Demonstratives [Grammar]
- Lesson 2: HAY [Grammar]
  - Describe a room [Practice]

### Module 5: Needs and obligations
- Lesson 1: Personal belongings [Vocabulary]
- Lesson 2: TENER QUE / DEBER [Grammar]
- Lesson 3: Daily obligations [Practice]

### Module 6: Wants and preferences
- Lesson 1: QUERER / NECESITAR [Grammar]
- Lesson 2: PREFERIR [Grammar]
  - Food and drinks [Vocabulary]
- Lesson 3: GUSTAR [Grammar]
- Lesson 4: Ordering food [Conversation]

### Module 7: Other essential verbs
- Lesson 1: PODER [Grammar]
- Lesson 2: HACER [Grammar]
- Lesson 3: Using common verbs [Practice]

### Module 8: Places and movement
- Lesson 1: Places in the city [Vocabulary]
- Lesson 2: IR [Grammar]
- Lesson 3: Adverbs of place [Grammar]
- Lesson 4: IR A + infinitive [Grammar]
- Lesson 5: Where are you going? [Conversation]

### Module 9: Questions and communication
- Lesson 1: Question words [Grammar]
- Lesson 2: Time and dates [Vocabulary]
- Lesson 3: Small talk [Conversation]

## A2 · Beginner

### Module 1: Present tense
- Lesson 1: Regular present tense [Grammar]
  - Daily activities [Vocabulary]
- Lesson 2: Irregular present tense [Grammar]
- Lesson 3: Present vs. progressive [Practice]
- Lesson 4: Frequency adverbs [Grammar]

### Module 2: Routines and reflexive verbs
- Lesson 1: Regular reflexive verbs for daily routines [Grammar]
  - Days and schedules [Vocabulary]
- Lesson 2: Irregular reflexive verbs for daily routines [Grammar]
- Lesson 3: Describing routines [Practice]
- Lesson 4: My weekly schedule [Conversation]
- Lesson 5: Talk about your day [Conversation]

### Module 3: Likes and reactions
- Lesson 1: ENCANTAR / INTERESAR [Grammar]
- Lesson 2: MOLESTAR / IMPORTAR [Grammar]
- Lesson 3: Likes and dislikes [Practice]
- Lesson 4: Discuss your interests [Conversation]

### Module 4: Everyday situations
- Lesson 1: Shopping and services [Vocabulary]
- Lesson 2: Asking for help [Conversation]
- Lesson 3: Solve an everyday situation [Practice]

## Reserved for a later stage

- Reflexive verbs beyond daily routines
- Preterite and imperfect
- Past tense contrasts
- Direct and indirect object pronouns
- Comparatives and superlatives
- Future simple
- Present perfect
- Invitations requiring conditional structures

These topics remain outside A1 Starter and the current A2 Beginner proposal pending an explicit placement decision.

## Before implementing a route

Match each entry to an existing post, identify missing material, and decide how to represent optional associated content in WordPress. Preserve existing lesson numbers when adding associated content.
