<?php
// Curated route display data. The editorial plan lives in docs/content-system/content-plan/.
if (!defined('ABSPATH')) { exit; }
function spanishnova_level_curriculum() {
  $text = <<<'CURRICULUM'
# A1 Starter
## First sentences
Basic nouns|Vocabulary|sustantivos-basicos
  Articles|Grammar
Basic verbs|Vocabulary
Adjectives|Grammar
  Physical descriptions|Vocabulary
SER|Grammar|ser-presente
  Describe people|Practice
  Professions|Vocabulary
ESTAR|Grammar|estar-presente
  Feelings and states|Vocabulary
TENER — Descriptions|Grammar
  Describe someone|Conversation
  Describe who someone is and where they are|Practice
## Actions in progress
ESTAR + gerund|Grammar|estar-gerundio
What are you doing?|Conversation
Present vs. progressive|Practice
## Possession
TENER — Possession|Grammar|tener-presente
Possessive adjectives|Grammar|adjetivos-posesivos
  Family members|Vocabulary
## Pointing and describing places
Demonstratives|Grammar|demostrativos
HAY|Grammar|hay
  Describe a room|Practice
## Needs and obligations
Personal belongings|Vocabulary
TENER QUE / DEBER|Grammar|tener-que-deber
Daily obligations|Practice
## Wants and preferences
QUERER / NECESITAR|Grammar
PREFERIR|Grammar
  Food and drinks|Vocabulary
GUSTAR|Grammar
Ordering food|Conversation
## Other essential verbs
PODER|Grammar
HACER|Grammar|hacer-presente
Using common verbs|Practice
## Places and movement
Places in the city|Vocabulary
IR|Grammar|ir-presente
Adverbs of place|Grammar|adverbios-lugar
IR A + infinitive|Grammar|ir-a-infinitivo
Where are you going?|Conversation
## Questions and communication
Question words|Grammar|pronombres-interrogativos
Time and dates|Vocabulary
Small talk|Conversation
# A2 Beginner
## Present tense
Regular present tense|Grammar|presente-indicativo-regulares
  Daily activities|Vocabulary
Irregular present tense|Grammar|presente-indicativo-irregulares
Present vs. progressive|Practice
Frequency adverbs|Grammar|adverbios-frecuencia
## Routines and reflexive verbs
Regular reflexive verbs for daily routines|Grammar|reflexivos-regulares-obvios
  Days and schedules|Vocabulary
Irregular reflexive verbs for daily routines|Grammar|reflexivos-irregulares
Describing routines|Practice
My weekly schedule|Conversation
Talk about your day|Conversation
## Likes and reactions
ENCANTAR / INTERESAR|Grammar|me-encanta-me-interesa
MOLESTAR / IMPORTAR|Grammar
Likes and dislikes|Practice
Discuss your interests|Conversation
## Everyday situations
Shopping and services|Vocabulary
Asking for help|Conversation
Solve an everyday situation|Practice
CURRICULUM;
  $routes = [];
  $level = '';
  $module_index = -1;
  foreach (explode("\n", $text) as $line) {
    if (strpos($line, '# ') === 0) {
      $level = strpos($line, 'A1 Starter') !== false ? 'starter' : 'beginner';
      $routes[$level] = [];
      $module_index = -1;
    } elseif (strpos($line, '## ') === 0) {
      $routes[$level][] = ['label' => substr($line, 3), 'items' => []];
      $module_index++;
    } elseif (trim($line) !== '') {
      $child = strpos($line, '  ') === 0;
      $parts = array_map('trim', explode('|', trim($line)));
      $entry = ['title' => $parts[0], 'type' => $parts[1] ?? '', 'slug' => $parts[2] ?? '', 'associated' => []];
      if ($child) {
        $last = count($routes[$level][$module_index]['items']) - 1;
        if ($last >= 0) $routes[$level][$module_index]['items'][$last]['associated'][] = $entry;
      } else {
        $routes[$level][$module_index]['items'][] = $entry;
      }
    }
  }
  return $routes;
}
