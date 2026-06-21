# Route intention

This document records the planning intention for SpanishNova guided routes.

## Core distinction

```txt
level_tax = broad learner level
route_tax = guided route term: beginner / intermediate / advanced
route_block = section inside the route
route_step = order inside the block
priority = production order, not route order
cpt = content type
```

`level_tax` and `route_tax` are not the same layer.

`level_tax` answers: who is this content for?

`route_tax` answers: does this content belong inside a guided route?

`route_block` and `route_step` answer: where does this item appear inside the route?

## URL rule

```txt
/level/{level}/ = general level archive
/route/{level}/ = guided route
```

Examples:

```txt
/level/beginner/ = all beginner content
/route/beginner/ = curated beginner path
```

The route must live in `route_tax`, not in `level_tax`.

## Route behavior

A route page should:

1. Detect the current `route_tax` term.
2. Query published route items for that term.
3. Group items by `route_block`.
4. Order items inside each block by `route_step`.
5. Show the block number, not a visible lesson number for every item.
6. Keep the structure open for grammar, vocabulary, readings, conversations, practice, and resources.

## Route data model

```txt
route_tax = beginner
route_block = 1
route_step = 5
```

`route_step` uses numeric order values. Smaller numbers appear first, and gaps can be left for later insertions.

A post can belong to a level without belonging to a route.

## Beginner intention

Beginner should build functional present-time communication.

Beginner starts with sentence creation and ends before the main past-tense system.

Beginner includes:

```txt
1 First sentences
2 Needs and obligation
3 Places and movement
4 Present actions
5 People and description
6 Wants and needs
7 Questions and routines
8 Opinions and reactions
9 Possession and reference
10 Reflexives: daily use
```

This means beginner can include topics that are sometimes labeled intermediate when they serve present-time communication: likes, reactions, possessive pronouns, demonstrative reference, and daily reflexives.

## Intermediate intention

Intermediate begins when the learner moves into time contrast and sentence manipulation.

Intermediate starts with past-tense use, especially the preterite.

Intermediate includes:

```txt
1 Past: preterite base
2 Object pronouns
3 Past: imperfect
4 Future
5 Pretérito perfecto
```

The main shift is not difficulty alone. The shift is functional: the learner starts telling what happened, connecting actions, replacing objects, and contrasting time.

## Advanced intention

Advanced should hold topics where meaning changes, nuance matters, or structures require contrast.

Advanced includes:

```txt
1 Reflexives: meaning and nuance
2 Past: difficult contrasts
3 Conditional / complex structures
```

Advanced is not just more grammar. It is grammar where the learner must control meaning, stance, emphasis, and contrast.

## Working principle

Grammar should support use.

Avoid routes that feel like:

```txt
Grammar -> Grammar -> Grammar -> Grammar
```

Prefer routes that can become:

```txt
Grammar -> Vocabulary -> Conversation -> Practice
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
