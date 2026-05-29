# Grammar HTML Fragment Template

Use this template to generate clean HTML fragment files for grammar posts in:

`docs/content-system/generated/generated-html-posts/grammar/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, or editor-specific block syntax.

## Fragment

```html
<section class="lesson-hero">
  <div class="breadcrumb">Grammar / {{grammar_tax}} / {{title_base}}</div>
  <div class="meta-row">
    <span class="pill">Grammar</span>
    <span class="pill">{{level_tax}}</span>
    <span class="pill">{{grammar_tax}}</span>
  </div>
  <h1>{{title}}</h1>
  <p class="intro">{{intro}}</p>
</section>

<section class="panel" id="overview">
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

<section class="panel" id="structure">
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

<section class="panel" id="conjugation">
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

<section class="panel" id="uses">
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

<section class="panel" id="oraciones">
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

<section class="panel" id="ejercicios">
  <h2>Ejercicios</h2>
  <div class="exercise-block">
    <h3 class="exercise-title">Seleccionar</h3>
    <ol class="exercise-list">
      <li>{{select_prompt_1}}<br>{{select_options_1}}</li>
      <li>{{select_prompt_2}}<br>{{select_options_2}}</li>
      <li>{{select_prompt_3}}<br>{{select_options_3}}</li>
      <li>{{select_prompt_4}}<br>{{select_options_4}}</li>
      <li>{{select_prompt_5}}<br>{{select_options_5}}</li>
      <li>{{select_prompt_6}}<br>{{select_options_6}}</li>
      <li>{{select_prompt_7}}<br>{{select_options_7}}</li>
      <li>{{select_prompt_8}}<br>{{select_options_8}}</li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Completar</h3>
    <ol class="exercise-list">
      <li><details class="exercise-item"><summary>{{complete_prompt_1}}</summary><span class="exercise-answer">{{complete_answer_1}}</span></details></li>
      <li><details class="exercise-item"><summary>{{complete_prompt_2}}</summary><span class="exercise-answer">{{complete_answer_2}}</span></details></li>
      <li><details class="exercise-item"><summary>{{complete_prompt_3}}</summary><span class="exercise-answer">{{complete_answer_3}}</span></details></li>
      <li><details class="exercise-item"><summary>{{complete_prompt_4}}</summary><span class="exercise-answer">{{complete_answer_4}}</span></details></li>
      <li><details class="exercise-item"><summary>{{complete_prompt_5}}</summary><span class="exercise-answer">{{complete_answer_5}}</span></details></li>
      <li><details class="exercise-item"><summary>{{complete_prompt_6}}</summary><span class="exercise-answer">{{complete_answer_6}}</span></details></li>
      <li><details class="exercise-item"><summary>{{complete_prompt_7}}</summary><span class="exercise-answer">{{complete_answer_7}}</span></details></li>
      <li><details class="exercise-item"><summary>{{complete_prompt_8}}</summary><span class="exercise-answer">{{complete_answer_8}}</span></details></li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Traducir</h3>
    <ol class="exercise-list">
      <li><details class="exercise-item"><summary>{{translate_prompt_1}}</summary><span class="exercise-answer">{{translate_answer_1}}</span></details></li>
      <li><details class="exercise-item"><summary>{{translate_prompt_2}}</summary><span class="exercise-answer">{{translate_answer_2}}</span></details></li>
      <li><details class="exercise-item"><summary>{{translate_prompt_3}}</summary><span class="exercise-answer">{{translate_answer_3}}</span></details></li>
      <li><details class="exercise-item"><summary>{{translate_prompt_4}}</summary><span class="exercise-answer">{{translate_answer_4}}</span></details></li>
      <li><details class="exercise-item"><summary>{{translate_prompt_5}}</summary><span class="exercise-answer">{{translate_answer_5}}</span></details></li>
      <li><details class="exercise-item"><summary>{{translate_prompt_6}}</summary><span class="exercise-answer">{{translate_answer_6}}</span></details></li>
      <li><details class="exercise-item"><summary>{{translate_prompt_7}}</summary><span class="exercise-answer">{{translate_answer_7}}</span></details></li>
      <li><details class="exercise-item"><summary>{{translate_prompt_8}}</summary><span class="exercise-answer">{{translate_answer_8}}</span></details></li>
    </ol>
  </div>
</section>

<section class="panel" id="wrap-up">
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
