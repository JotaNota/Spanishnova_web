# Beginner route intention

This document records the planning intention for the SpanishNova Beginner route.

## Problem

`/level/beginner/` should not be a flat archive of every post marked as beginner.

The page should still respect `level_tax=beginner`, but it needs another layer to decide which items belong to the guided path and where they appear.

## Core distinction

```txt
level_tax = who the content is for
route_tax = where the content appears in the route
priority = order of appearance
cpt = content type
```

`level_tax` is not enough for route design. It can collect grammar, vocabulary, readings, conversations, practice, and resources, but it cannot decide sequence or learning function.

`route_tax` defines route blocks.

`priority` remains a numeric ordering field. It should not be used for labels such as `b1`.

## Route behavior

`/level/beginner/` should:

1. Detect the current level term.
2. Query published content with that `level_tax`.
3. Separate content that has `route_tax` from content that does not.
4. Display route content first, grouped by `route_tax`.
5. Order route items by `priority` or `menu_order`.
6. Display content without `route_tax` below as additional beginner content.

## Scope

Start with Beginner.

Start with Grammar route items where available, but keep the structure open for vocabulary, conversations, readings, practice, and resources.

Do not make `/level/beginner/` a grammar-only page.

Do not place every beginner item inside the route. The route should be curated.

## Design direction

The visual route should be vertical, with blocks and a light zigzag feeling. It should help the learner know where to begin and how to continue.

The route should not become a list of 80 or 100 lessons.

Each block should contain a small number of route items and should mix content types when the system is ready.

## Initial rule

Use route blocks, not a flat lesson list.

A good block pattern:

```txt
Grammar -> Vocabulary -> Conversation -> Practice
```

A weak block pattern:

```txt
Grammar -> Grammar -> Grammar -> Grammar -> Grammar
```

## Proposed data model

```txt
level_tax = beginner
route_tax = beginner-04-daily-actions
priority = numeric order
cpt = grammar / vocabulary / conversation / practice / reading / resource
```

## Deferred ideas

Do not implement these yet:

- user progress
- completed lessons
- locked lessons
- route analytics
- user accounts
- lesson prerequisites
- route dashboards

The first goal is structure, not gamification.
