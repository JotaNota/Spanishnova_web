# Grammar HTML Fragment Template

Use this template to generate clean HTML fragment files for grammar posts in:

`docs/content-system/generated/generated-html-posts/grammar/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, or editor-specific block syntax.

## Fragment

```html
<div class="sn-lesson-wrap">
  <div class="sn-lesson-layout">
    <article class="sn-lesson-article">
<section class="sn-lesson-hero">
  <div class="sn-breadcrumb">Grammar / {{grammar_tax}} / {{topic_base}}</div>
  <div class="sn-meta-row">
    <span class="sn-pill">Grammar</span>
    <span class="sn-pill">{{level_tax}}</span>
    <span class="sn-pill">{{grammar_tax}}</span>
  </div>
  <h1>{{public_title}}</h1>
  <p class="sn-intro">{{intro}}</p>
</section>

<section class="sn-panel" id="overview">
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

<section class="sn-panel" id="structure">
  <h2>Structure</h2>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Structure</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{structure_1}}</td>
        <td>{{structure_example_1}}</td>
        <td class="translation">{{structure_translation_1}}</td>
      </tr>
      <tr>
        <td>{{structure_2}}</td>
        <td>{{structure_example_2}}</td>
        <td class="translation">{{structure_translation_2}}</td>
      </tr>
      <tr>
        <td>{{structure_3}}</td>
        <td>{{structure_example_3}}</td>
        <td class="translation">{{structure_translation_3}}</td>
      </tr>
    </tbody>
  </table>
</section>

<section class="sn-panel" id="conjugation">
  <h2>Conjugation</h2>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Form</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>Yo</td><td class="verb-form">{{yo_form}}</td><td>{{yo_example}}</td><td class="translation">{{yo_translation}}</td></tr>
      <tr><td>Tú</td><td class="verb-form">{{tu_form}}</td><td>{{tu_example}}</td><td class="translation">{{tu_translation}}</td></tr>
      <tr><td>Él / Ella / Usted</td><td class="verb-form">{{el_form}}</td><td>{{el_example}}</td><td class="translation">{{el_translation}}</td></tr>
      <tr><td>Nosotros / Nosotras</td><td class="verb-form">{{nosotros_form}}</td><td>{{nosotros_example}}</td><td class="translation">{{nosotros_translation}}</td></tr>
      <tr><td>Ellos / Ellas / Ustedes</td><td class="verb-form">{{ellos_form}}</td><td>{{ellos_example}}</td><td class="translation">{{ellos_translation}}</td></tr>
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

    <button type="button" class="sn-conj-check">Check answers</button>
  </div>
</section>

<section class="sn-panel" id="uses">
  <h2>Uses</h2>
  <div class="two-column">
    <div class="example-group">
      <h3>{{use_1_heading}}</h3>
      <p>{{use_1_explanation}}</p>
      <ul class="sentence-list">
        <li><strong>{{use_1_example_1}}</strong> <span class="translation">{{use_1_translation_1}}</span></li>
        <li><strong>{{use_1_example_2}}</strong> <span class="translation">{{use_1_translation_2}}</span></li>
      </ul>
    </div>
    <div class="example-group">
      <h3>{{use_2_heading}}</h3>
      <p>{{use_2_explanation}}</p>
      <ul class="sentence-list">
        <li><strong>{{use_2_example_1}}</strong> <span class="translation">{{use_2_translation_1}}</span></li>
        <li><strong>{{use_2_example_2}}</strong> <span class="translation">{{use_2_translation_2}}</span></li>
      </ul>
    </div>
  </div>
</section>

<section class="sn-panel" id="oraciones">
  <h2>Oraciones</h2>
  <div class="two-column">
    <div class="example-group">
      <h3>Afirmativa</h3>
      <ul class="sentence-list">
        <li><strong>{{affirmative_1}}</strong> <span class="translation">{{affirmative_translation_1}}</span></li>
        <li><strong>{{affirmative_2}}</strong> <span class="translation">{{affirmative_translation_2}}</span></li>
        <li><strong>{{affirmative_3}}</strong> <span class="translation">{{affirmative_translation_3}}</span></li>
        <li><strong>{{affirmative_4}}</strong> <span class="translation">{{affirmative_translation_4}}</span></li>
      </ul>
    </div>
    <div class="example-group">
      <h3>Negativa</h3>
      <ul class="sentence-list">
        <li><strong>{{negative_1}}</strong> <span class="translation">{{negative_translation_1}}</span></li>
        <li><strong>{{negative_2}}</strong> <span class="translation">{{negative_translation_2}}</span></li>
        <li><strong>{{negative_3}}</strong> <span class="translation">{{negative_translation_3}}</span></li>
        <li><strong>{{negative_4}}</strong> <span class="translation">{{negative_translation_4}}</span></li>
      </ul>
    </div>
  </div>
  <div class="example-bubble">
    <h3>Preguntas</h3>
    <ul class="question-list">
      <li><strong>{{question_1}}</strong><br><span class="translation">{{question_translation_1}}</span></li>
      <li><strong>{{question_2}}</strong><br><span class="translation">{{question_translation_2}}</span></li>
      <li><strong>{{question_3}}</strong><br><span class="translation">{{question_translation_3}}</span></li>
      <li><strong>{{question_4}}</strong><br><span class="translation">{{question_translation_4}}</span></li>
    </ul>
  </div>
</section>

<section class="sn-panel" id="ejercicios">
  <h2>Ejercicios</h2>
  <div class="exercise-block">
    <h3 class="exercise-title">Seleccionar</h3>
    <ol class="exercise-list">
      <li>
        <p>{{select_prompt_1}}</p>
        <ol type="a">
          <li>{{select_option_1_a}}</li>
          <li>{{select_option_1_b}}</li>
          <li>{{select_option_1_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_1_before}}<strong>{{select_answer_1_key}}</strong>{{select_answer_1_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_2}}</p>
        <ol type="a">
          <li>{{select_option_2_a}}</li>
          <li>{{select_option_2_b}}</li>
          <li>{{select_option_2_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_2_before}}<strong>{{select_answer_2_key}}</strong>{{select_answer_2_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_3}}</p>
        <ol type="a">
          <li>{{select_option_3_a}}</li>
          <li>{{select_option_3_b}}</li>
          <li>{{select_option_3_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_3_before}}<strong>{{select_answer_3_key}}</strong>{{select_answer_3_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_4}}</p>
        <ol type="a">
          <li>{{select_option_4_a}}</li>
          <li>{{select_option_4_b}}</li>
          <li>{{select_option_4_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_4_before}}<strong>{{select_answer_4_key}}</strong>{{select_answer_4_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_5}}</p>
        <ol type="a">
          <li>{{select_option_5_a}}</li>
          <li>{{select_option_5_b}}</li>
          <li>{{select_option_5_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_5_before}}<strong>{{select_answer_5_key}}</strong>{{select_answer_5_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_6}}</p>
        <ol type="a">
          <li>{{select_option_6_a}}</li>
          <li>{{select_option_6_b}}</li>
          <li>{{select_option_6_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_6_before}}<strong>{{select_answer_6_key}}</strong>{{select_answer_6_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_7}}</p>
        <ol type="a">
          <li>{{select_option_7_a}}</li>
          <li>{{select_option_7_b}}</li>
          <li>{{select_option_7_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_7_before}}<strong>{{select_answer_7_key}}</strong>{{select_answer_7_after}}</p></details>
      </li>
      <li>
        <p>{{select_prompt_8}}</p>
        <ol type="a">
          <li>{{select_option_8_a}}</li>
          <li>{{select_option_8_b}}</li>
          <li>{{select_option_8_c}}</li>
        </ol>
        <details><summary>Mostrar respuesta</summary><p>{{select_answer_8_before}}<strong>{{select_answer_8_key}}</strong>{{select_answer_8_after}}</p></details>
      </li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Completar</h3>
    <ol class="exercise-list">
      <li><details><summary>{{complete_prompt_1}}</summary><p>{{complete_answer_1_before}}<strong>{{complete_answer_1_key}}</strong>{{complete_answer_1_after}}</p></details></li>
      <li><details><summary>{{complete_prompt_2}}</summary><p>{{complete_answer_2_before}}<strong>{{complete_answer_2_key}}</strong>{{complete_answer_2_after}}</p></details></li>
      <li><details><summary>{{complete_prompt_3}}</summary><p>{{complete_answer_3_before}}<strong>{{complete_answer_3_key}}</strong>{{complete_answer_3_after}}</p></details></li>
      <li><details><summary>{{complete_prompt_4}}</summary><p>{{complete_answer_4_before}}<strong>{{complete_answer_4_key}}</strong>{{complete_answer_4_after}}</p></details></li>
      <li><details><summary>{{complete_prompt_5}}</summary><p>{{complete_answer_5_before}}<strong>{{complete_answer_5_key}}</strong>{{complete_answer_5_after}}</p></details></li>
      <li><details><summary>{{complete_prompt_6}}</summary><p>{{complete_answer_6_before}}<strong>{{complete_answer_6_key}}</strong>{{complete_answer_6_after}}</p></details></li>
      <li><details><summary>{{complete_prompt_7}}</summary><p>{{complete_answer_7_before}}<strong>{{complete_answer_7_key}}</strong>{{complete_answer_7_after}}</p></details></li>
      <li><details><summary>{{complete_prompt_8}}</summary><p>{{complete_answer_8_before}}<strong>{{complete_answer_8_key}}</strong>{{complete_answer_8_after}}</p></details></li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Traducir</h3>
    <ol class="exercise-list">
      <li><details><summary>{{translate_prompt_1}}</summary><p>{{translate_answer_1_before}}<strong>{{translate_answer_1_key}}</strong>{{translate_answer_1_after}}</p></details></li>
      <li><details><summary>{{translate_prompt_2}}</summary><p>{{translate_answer_2_before}}<strong>{{translate_answer_2_key}}</strong>{{translate_answer_2_after}}</p></details></li>
      <li><details><summary>{{translate_prompt_3}}</summary><p>{{translate_answer_3_before}}<strong>{{translate_answer_3_key}}</strong>{{translate_answer_3_after}}</p></details></li>
      <li><details><summary>{{translate_prompt_4}}</summary><p>{{translate_answer_4_before}}<strong>{{translate_answer_4_key}}</strong>{{translate_answer_4_after}}</p></details></li>
      <li><details><summary>{{translate_prompt_5}}</summary><p>{{translate_answer_5_before}}<strong>{{translate_answer_5_key}}</strong>{{translate_answer_5_after}}</p></details></li>
      <li><details><summary>{{translate_prompt_6}}</summary><p>{{translate_answer_6_before}}<strong>{{translate_answer_6_key}}</strong>{{translate_answer_6_after}}</p></details></li>
      <li><details><summary>{{translate_prompt_7}}</summary><p>{{translate_answer_7_before}}<strong>{{translate_answer_7_key}}</strong>{{translate_answer_7_after}}</p></details></li>
      <li><details><summary>{{translate_prompt_8}}</summary><p>{{translate_answer_8_before}}<strong>{{translate_answer_8_key}}</strong>{{translate_answer_8_after}}</p></details></li>
    </ol>
  </div>
</section>

<section class="sn-panel" id="wrap-up">
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
    </article>
  </div>
</div>
```

## Conjugation Practice Marker

Use `sn-conjugation-practice` only when the lesson is `lesson_type=verb-usage` and the target is one simple isolated verb.

Do not use `sn-conjugation-practice` for structures, compound phrases, periphrases, or expressions such as `tener que`, `hay algo`, or `he tenido problemas`.
