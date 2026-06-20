# Beginner route skeleton

This document records the working skeleton for the SpanishNova Beginner route.

Beginner is a guided present-time path. It should not become every beginner post.

## Route limit

```txt
Beginner Path
├─ 10 blocks maximum
├─ 3 to 5 route items per block
├─ 35 to 45 route items total when other content types are added
├─ 2 to 3 grammar items per block in most blocks
├─ vocabulary, conversation, reading, practice, or resource items can be added later
└─ extra beginner content remains outside the route
```

## Route model

```txt
route_tax = beginner
route_block = 01 / 02 / 03...
route_step = 10 / 20 / 30...
```

`route_tax` identifies the guided route. `route_block` creates the visible route section. `route_step` orders items inside that section.

## Block 01 · First sentences

Function: create simple sentences.

Grammar route items:

```txt
10 ser-presente
20 estar-presente
30 hay
40 sustantivos-basicos
```

Future supporting content:

```txt
Vocabulary: people, places, basic nouns
Conversation: introducing yourself / describing where things are
Practice: first sentence patterns
```

## Block 02 · Needs and obligation

Function: say what someone has, needs, or must do.

Grammar route items:

```txt
10 tener-presente
20 hay-que
30 tener-que-deber
40 verbos-basicos-tener-que
```

Future supporting content:

```txt
Vocabulary: objects, home, classroom
Practice: needs and obligations
Conversation: what do you need to do?
```

## Block 03 · Places and movement

Function: talk about places, movement, and plans.

Grammar route items:

```txt
10 ir-presente
20 ir-a-infinitivo
30 adverbios-lugar
```

Future supporting content:

```txt
Vocabulary: city, places, transportation
Conversation: asking where someone is going
Practice: places and movement
```

## Block 04 · Present actions

Function: talk about routines and what is happening now.

Grammar route items:

```txt
10 hacer-presente
20 presente-indicativo-regulares
30 presente-indicativo-irregulares
40 estar-gerundio
```

Future supporting content:

```txt
Vocabulary: daily actions, common verbs
Conversation: what are you doing today?
Practice: present tense contrast
```

## Block 05 · People and description

Function: describe people and things.

Grammar route items:

```txt
10 adjetivos-posesivos
20 demostrativos
```

Future supporting content:

```txt
Vocabulary: family, appearance, personality, clothes
Reading: describing a person
Practice: description review
```

## Block 06 · Wants and needs

Function: express wants, preferences, and needs.

Grammar route items:

```txt
10 querer-preferir-necesitar
```

Future supporting content:

```txt
Vocabulary: food, café, restaurant
Conversation: ordering at a café
Practice: wants and preferences
```

## Block 07 · Questions and routines

Function: ask questions and describe routine frequency.

Grammar route items:

```txt
10 pronombres-interrogativos
20 adverbios-frecuencia
```

Future supporting content:

```txt
Vocabulary: time, days, activities
Conversation: small talk / making plans
Practice: questions and answers
```

## Block 08 · Opinions and reactions

Function: express likes, interests, reactions, and opinions in the present.

Grammar route items:

```txt
10 gustar-encantar-interesar
20 me-encanta-me-interesa
30 me-molesta-fascina-importa
```

Future supporting content:

```txt
Vocabulary: hobbies, media, daily preferences
Conversation: talking about what you like
Practice: reaction patterns
```

## Block 09 · Possession and reference

Function: refer to things as mine, yours, this one, or that one.

Grammar route items:

```txt
10 pronombres-posesivos
20 pronombres-posesivos-demostrativos
```

Future supporting content:

```txt
Vocabulary: personal objects, clothes, classroom objects
Conversation: choosing and comparing objects
Practice: possession and reference
```

## Block 10 · Reflexives: daily use

Function: talk about daily actions where the subject acts on itself.

Grammar route items:

```txt
10 reflexivos-regulares-obvios
20 reflexivos-rutina-diaria
30 reflexivos-irregulares
```

Future supporting content:

```txt
Vocabulary: morning routine, hygiene, daily schedule
Conversation: daily routine
Practice: routine sequence
```

## Boundary

Beginner ends before the main past-tense route.

The next route should begin with:

```txt
Intermediate 01 · Past: preterite base
```

## Working principle

Grammar should support use.

Avoid blocks that feel like:

```txt
Grammar -> Grammar -> Grammar -> Grammar
```

Prefer blocks that can become:

```txt
Grammar -> Vocabulary -> Conversation -> Practice
```

## Content outside the route

Not all beginner content belongs to the route.

Some posts can remain visible under additional beginner content on `/level/beginner/`.

A post can be beginner without being part of the guided route.
