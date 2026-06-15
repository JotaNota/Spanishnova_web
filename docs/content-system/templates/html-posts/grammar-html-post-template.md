# Grammar HTML Fragment Template

Use this template to generate clean HTML fragment files for grammar posts in:

`docs/content-system/generated/generated-html-posts/grammar/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, or editor-specific block syntax.

## Fragment

```html
  <p class="sn-intro">{{intro}}</p>

<section id="overview">
  <h2>Overview</h2>
  <p>{{overview}}</p>
  <div class="simple-example">
    <div class="example-line">
      <strong>{{example_es}}</strong>
      <span class="example-arrow">→</span>
      <em>{{example_en}}</em>
    </div>
    <div class="use-line"><span class="use-label">Use:</span> {{use_note}}</div>
  </div>
</section>

<section id="conjugation">
  <h2>Conjugation</h2>

  <h3>Regular -ar Verbs</h3>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Ending</th>
        <th>{{ar_infinitive}}</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>Yo</td><td class="verb-form">{{ar_yo_ending}}</td><td class="verb-form">{{ar_yo_form}}</td><td>{{ar_yo_example}}</td><td class="translation">{{ar_yo_translation}}</td></tr>
      <tr><td>Tú</td><td class="verb-form">{{ar_tu_ending}}</td><td class="verb-form">{{ar_tu_form}}</td><td>{{ar_tu_example}}</td><td class="translation">{{ar_tu_translation}}</td></tr>
      <tr><td>Él / Ella / Usted</td><td class="verb-form">{{ar_el_ending}}</td><td class="verb-form">{{ar_el_form}}</td><td>{{ar_el_example}}</td><td class="translation">{{ar_el_translation}}</td></tr>
      <tr><td>Nosotros / Nosotras</td><td class="verb-form">{{ar_nosotros_ending}}</td><td class="verb-form">{{ar_nosotros_form}}</td><td>{{ar_nosotros_example}}</td><td class="translation">{{ar_nosotros_translation}}</td></tr>
      <tr><td>Ellos / Ellas / Ustedes</td><td class="verb-form">{{ar_ellos_ending}}</td><td class="verb-form">{{ar_ellos_form}}</td><td>{{ar_ellos_example}}</td><td class="translation">{{ar_ellos_translation}}</td></tr>
    </tbody>
  </table>

  <h3>Regular -er Verbs</h3>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Ending</th>
        <th>{{er_infinitive}}</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>Yo</td><td class="verb-form">{{er_yo_ending}}</td><td class="verb-form">{{er_yo_form}}</td><td>{{er_yo_example}}</td><td class="translation">{{er_yo_translation}}</td></tr>
      <tr><td>Tú</td><td class="verb-form">{{er_tu_ending}}</td><td class="verb-form">{{er_tu_form}}</td><td>{{er_tu_example}}</td><td class="translation">{{er_tu_translation}}</td></tr>
      <tr><td>Él / Ella / Usted</td><td class="verb-form">{{er_el_ending}}</td><td class="verb-form">{{er_el_form}}</td><td>{{er_el_example}}</td><td class="translation">{{er_el_translation}}</td></tr>
      <tr><td>Nosotros / Nosotras</td><td class="verb-form">{{er_nosotros_ending}}</td><td class="verb-form">{{er_nosotros_form}}</td><td>{{er_nosotros_example}}</td><td class="translation">{{er_nosotros_translation}}</td></tr>
      <tr><td>Ellos / Ellas / Ustedes</td><td class="verb-form">{{er_ellos_ending}}</td><td class="verb-form">{{er_ellos_form}}</td><td>{{er_ellos_example}}</td><td class="translation">{{er_ellos_translation}}</td></tr>
    </tbody>
  </table>

  <h3>Regular -ir Verbs</h3>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Ending</th>
        <th>{{ir_infinitive}}</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>Yo</td><td class="verb-form">{{ir_yo_ending}}</td><td class="verb-form">{{ir_yo_form}}</td><td>{{ir_yo_example}}</td><td class="translation">{{ir_yo_translation}}</td></tr>
      <tr><td>Tú</td><td class="verb-form">{{ir_tu_ending}}</td><td class="verb-form">{{ir_tu_form}}</td><td>{{ir_tu_example}}</td><td class="translation">{{ir_tu_translation}}</td></tr>
      <tr><td>Él / Ella / Usted</td><td class="verb-form">{{ir_el_ending}}</td><td class="verb-form">{{ir_el_form}}</td><td>{{ir_el_example}}</td><td class="translation">{{ir_el_translation}}</td></tr>
      <tr><td>Nosotros / Nosotras</td><td class="verb-form">{{ir_nosotros_ending}}</td><td class="verb-form">{{ir_nosotros_form}}</td><td>{{ir_nosotros_example}}</td><td class="translation">{{ir_nosotros_translation}}</td></tr>
      <tr><td>Ellos / Ellas / Ustedes</td><td class="verb-form">{{ir_ellos_ending}}</td><td class="verb-form">{{ir_ellos_form}}</td><td>{{ir_ellos_example}}</td><td class="translation">{{ir_ellos_translation}}</td></tr>
    </tbody>
  </table>

  <div class="sn-conjugation-practice">
    <table class="sn-conj-table">
      <tbody>
        <tr>
          <th>Yo</th>
          <td><input type="text" data-answer="{{yo_form}}"></td>
          <td class="sn-conj-feedback"></td>
        </tr>
        <tr>
          <th>Tú</th>
          <td><input type="text" data-answer="{{tu_form}}"></td>
          <td class="sn-conj-feedback"></td>
        </tr>
        <tr>
          <th>Él / Ella / Usted</th>
          <td><input type="text" data-answer="{{el_form}}"></td>
          <td class="sn-conj-feedback"></td>
        </tr>
        <tr>
          <th>Nosotros / Nosotras</th>
          <td><input type="text" data-answer="{{nosotros_form}}"></td>
          <td class="sn-conj-feedback"></td>
        </tr>
        <tr>
          <th>Ellos / Ellas / Ustedes</th>
          <td><input type="text" data-answer="{{ellos_form}}"></td>
          <td class="sn-conj-feedback"></td>
        </tr>
      </tbody>
    </table>

    <button type="button" class="sn-conj-check">Respuestas</button>
    <button type="button" class="sn-conj-reset">Reiniciar</button>
  </div>
</section>

For `lesson_type=verb-usage` with one isolated verb, always include both conjugation buttons:

- `Respuestas`
- `Reiniciar`

Do not use `Check answers`. Do not omit the reset button.

<section id="uses">
  <h2>Uses</h2>
  <!-- Choose vertical sections or grouped columns based on the lesson. Do not force two-column. -->
  <div class="two-column">
    <div class="example-group">
      <h3>{{use_1_heading}}</h3>
      <p>{{use_1_explanation}}</p>
      <ul class="sentence-list">
        <li><strong>{{use_1_example_1}}</strong> - {{use_1_translation_1}}</li>
        <li><strong>{{use_1_example_2}}</strong> - {{use_1_translation_2}}</li>
      </ul>
    </div>
    <div class="example-group">
      <h3>{{use_2_heading}}</h3>
      <p>{{use_2_explanation}}</p>
      <ul class="sentence-list">
        <li><strong>{{use_2_example_1}}</strong> - {{use_2_translation_1}}</li>
        <li><strong>{{use_2_example_2}}</strong> - {{use_2_translation_2}}</li>
      </ul>
    </div>
  </div>
</section>

Use sections should adapt to the verb or structure. Examples:

- `ser`: Names, Nationalities, Professions, Relationships, Physical descriptions and personality.
- `estar`: Location, Temporary states, Emotions, Conditions.
- `tener`: Possession, Age, Hunger/thirst, Common expressions.

Keep Spanish and English examples on the same line as `Spanish - English`.

For `lesson_type=comparison`, use this visible section order:

- `Overview`
- `Cómo/cuándo lo usamos`
- `Comparación`
- `Afirmativas`
- `Negativas`
- `Preguntas`
- `Ejercicios`

<section id="oraciones">
  <h2>Oraciones.</h2>
  <!-- Choose vertical sections or grouped columns based on the lesson. Do not force two-column. -->
  <div class="two-column">
    <div class="example-group">
      <h3>Afirmativas</h3>
      <ul class="sentence-list">
        <li><strong>{{affirmative_1}}</strong> - {{affirmative_translation_1}}</li>
        <li><strong>{{affirmative_2}}</strong> - {{affirmative_translation_2}}</li>
        <li><strong>{{affirmative_3}}</strong> - {{affirmative_translation_3}}</li>
        <li><strong>{{affirmative_4}}</strong> - {{affirmative_translation_4}}</li>
      </ul>
    </div>
    <div class="example-group">
      <h3>Negativas</h3>
      <ul class="sentence-list">
        <li><strong>{{negative_1}}</strong> - {{negative_translation_1}}</li>
        <li><strong>{{negative_2}}</strong> - {{negative_translation_2}}</li>
        <li><strong>{{negative_3}}</strong> - {{negative_translation_3}}</li>
        <li><strong>{{negative_4}}</strong> - {{negative_translation_4}}</li>
      </ul>
    </div>
  </div>
  <div class="example-bubble">
    <h3>Forma de pregunta</h3>
    <ul class="question-list">
      <li><strong>{{question_1}}</strong> - {{question_translation_1}}</li>
      <li><strong>{{question_2}}</strong> - {{question_translation_2}}</li>
      <li><strong>{{question_3}}</strong> - {{question_translation_3}}</li>
      <li><strong>{{question_4}}</strong> - {{question_translation_4}}</li>
    </ul>
  </div>
</section>

<section id="ejercicios">
  <h2>Ejercicios</h2>
  <div class="exercise-block">
    <h3 class="exercise-title">Selección simple</h3>
    <ol class="exercise-list">
      <li>
        <p>{{select_prompt_1}}</p>
        <ol type="a">
          <li>{{select_option_1_a}}</li>
          <li>{{select_option_1_b}}</li>
          <li>{{select_option_1_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_1_before}}<strong>{{select_answer_1_key}}</strong>{{select_answer_1_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_2}}</p>
        <ol type="a">
          <li>{{select_option_2_a}}</li>
          <li>{{select_option_2_b}}</li>
          <li>{{select_option_2_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_2_before}}<strong>{{select_answer_2_key}}</strong>{{select_answer_2_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_3}}</p>
        <ol type="a">
          <li>{{select_option_3_a}}</li>
          <li>{{select_option_3_b}}</li>
          <li>{{select_option_3_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_3_before}}<strong>{{select_answer_3_key}}</strong>{{select_answer_3_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_4}}</p>
        <ol type="a">
          <li>{{select_option_4_a}}</li>
          <li>{{select_option_4_b}}</li>
          <li>{{select_option_4_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_4_before}}<strong>{{select_answer_4_key}}</strong>{{select_answer_4_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_5}}</p>
        <ol type="a">
          <li>{{select_option_5_a}}</li>
          <li>{{select_option_5_b}}</li>
          <li>{{select_option_5_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_5_before}}<strong>{{select_answer_5_key}}</strong>{{select_answer_5_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_6}}</p>
        <ol type="a">
          <li>{{select_option_6_a}}</li>
          <li>{{select_option_6_b}}</li>
          <li>{{select_option_6_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_6_before}}<strong>{{select_answer_6_key}}</strong>{{select_answer_6_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_7}}</p>
        <ol type="a">
          <li>{{select_option_7_a}}</li>
          <li>{{select_option_7_b}}</li>
          <li>{{select_option_7_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_7_before}}<strong>{{select_answer_7_key}}</strong>{{select_answer_7_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_8}}</p>
        <ol type="a">
          <li>{{select_option_8_a}}</li>
          <li>{{select_option_8_b}}</li>
          <li>{{select_option_8_c}}</li>
        </ol>
        <details><summary>Respuesta</summary><p>{{select_answer_8_before}}<strong>{{select_answer_8_key}}</strong>{{select_answer_8_after}}</p></details>
      </li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Completar</h3>
    <ol class="exercise-list">
      <li><p>{{complete_prompt_1}}</p><details><summary>Respuesta</summary><p>{{complete_answer_1_before}}<strong>{{complete_answer_1_key}}</strong>{{complete_answer_1_after}}</p></details></li>
      <li><p>{{complete_prompt_2}}</p><details><summary>Respuesta</summary><p>{{complete_answer_2_before}}<strong>{{complete_answer_2_key}}</strong>{{complete_answer_2_after}}</p></details></li>
      <li><p>{{complete_prompt_3}}</p><details><summary>Respuesta</summary><p>{{complete_answer_3_before}}<strong>{{complete_answer_3_key}}</strong>{{complete_answer_3_after}}</p></details></li>
      <li><p>{{complete_prompt_4}}</p><details><summary>Respuesta</summary><p>{{complete_answer_4_before}}<strong>{{complete_answer_4_key}}</strong>{{complete_answer_4_after}}</p></details></li>
      <li><p>{{complete_prompt_5}}</p><details><summary>Respuesta</summary><p>{{complete_answer_5_before}}<strong>{{complete_answer_5_key}}</strong>{{complete_answer_5_after}}</p></details></li>
      <li><p>{{complete_prompt_6}}</p><details><summary>Respuesta</summary><p>{{complete_answer_6_before}}<strong>{{complete_answer_6_key}}</strong>{{complete_answer_6_after}}</p></details></li>
      <li><p>{{complete_prompt_7}}</p><details><summary>Respuesta</summary><p>{{complete_answer_7_before}}<strong>{{complete_answer_7_key}}</strong>{{complete_answer_7_after}}</p></details></li>
      <li><p>{{complete_prompt_8}}</p><details><summary>Respuesta</summary><p>{{complete_answer_8_before}}<strong>{{complete_answer_8_key}}</strong>{{complete_answer_8_after}}</p></details></li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Traducción</h3>
    <ol class="exercise-list">
      <li><p>{{translate_prompt_1}}</p><details><summary>Respuesta</summary><p>{{translate_answer_1_before}}<strong>{{translate_answer_1_key}}</strong>{{translate_answer_1_after}}</p></details></li>
      <li><p>{{translate_prompt_2}}</p><details><summary>Respuesta</summary><p>{{translate_answer_2_before}}<strong>{{translate_answer_2_key}}</strong>{{translate_answer_2_after}}</p></details></li>
      <li><p>{{translate_prompt_3}}</p><details><summary>Respuesta</summary><p>{{translate_answer_3_before}}<strong>{{translate_answer_3_key}}</strong>{{translate_answer_3_after}}</p></details></li>
      <li><p>{{translate_prompt_4}}</p><details><summary>Respuesta</summary><p>{{translate_answer_4_before}}<strong>{{translate_answer_4_key}}</strong>{{translate_answer_4_after}}</p></details></li>
      <li><p>{{translate_prompt_5}}</p><details><summary>Respuesta</summary><p>{{translate_answer_5_before}}<strong>{{translate_answer_5_key}}</strong>{{translate_answer_5_after}}</p></details></li>
      <li><p>{{translate_prompt_6}}</p><details><summary>Respuesta</summary><p>{{translate_answer_6_before}}<strong>{{translate_answer_6_key}}</strong>{{translate_answer_6_after}}</p></details></li>
      <li><p>{{translate_prompt_7}}</p><details><summary>Respuesta</summary><p>{{translate_answer_7_before}}<strong>{{translate_answer_7_key}}</strong>{{translate_answer_7_after}}</p></details></li>
      <li><p>{{translate_prompt_8}}</p><details><summary>Respuesta</summary><p>{{translate_answer_8_before}}<strong>{{translate_answer_8_key}}</strong>{{translate_answer_8_after}}</p></details></li>
    </ol>
  </div>
</section>

<section id="wrap-up">
  <h2>Wrap Up</h2>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Use</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>{{wrap_use_1}}</td><td>{{wrap_example_1}}</td><td class="translation">{{wrap_translation_1}}</td></tr>
      <tr><td>{{wrap_use_2}}</td><td>{{wrap_example_2}}</td><td class="translation">{{wrap_translation_2}}</td></tr>
      <tr><td>{{wrap_use_3}}</td><td>{{wrap_example_3}}</td><td class="translation">{{wrap_translation_3}}</td></tr>
      <tr><td>{{wrap_use_4}}</td><td>{{wrap_example_4}}</td><td class="translation">{{wrap_translation_4}}</td></tr>
    </tbody>
  </table>
</section>

```

## Conjugation Practice Marker

Use `sn-conjugation-practice` only when the lesson is `lesson_type=verb-usage` and the target is one simple isolated verb.

Do not use `sn-conjugation-practice` for structures, compound phrases, periphrases, or expressions such as `tener que`, `hay algo`, or `he tenido problemas`.

## Exercise Variation Rules

Use these exercise subsection headings when the exercise type is present:

- `<h3 class="exercise-title">Selección simple</h3>`
- `<h3 class="exercise-title">Completar</h3>`
- `<h3 class="exercise-title">Traducción</h3>`

`Selección simple` is optional. Use only `select`, `complete`, and `translate` exercise groups.

For grammar exercises, do not reuse the same sentences or the same subject order across `Selección simple`, `Completar`, and `Traducción`.

- Vary subject order instead of following the conjugation table from `Yo` to `Ellos`.
- Mix `yo`, `tú`, `él/ella/usted`, `nosotros`, and `ellos/ustedes`.
- Use different sentences in each exercise section.
- Avoid repeating exact examples already used in `Overview`, `Conjugation`, `Uses`, or `Oraciones`.
- Include affirmative, negative, and question prompts when the structure allows it.
- Keep the current `<details><summary>Respuesta</summary>...</details>` answer format.
