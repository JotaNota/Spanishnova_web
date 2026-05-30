# Reading HTML Fragment Template

Use this template to generate clean HTML fragment files for reading posts in:

`docs/content-system/generated/generated-html-posts/readings/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, or editor-specific block syntax.

## Fragment

```html
<div class="sn-lesson-wrap">
  <div class="sn-lesson-layout">
    <article class="sn-lesson-article">
<section class="sn-lesson-hero">
  <div class="sn-breadcrumb">Readings / {{topic_tax}} / {{title_base}}</div>
  <div class="sn-meta-row">
    <span class="sn-pill">Readings</span>
    <span class="sn-pill">{{level_tax}}</span>
    <span class="sn-pill">{{topic_tax}}</span>
  </div>
  <h1>{{title}}</h1>
  <p class="sn-intro">{{intro}}</p>
</section>

<section class="sn-panel" id="key-vocabulary">
  <h2>Key Vocabulary</h2>
  <table class="compact-table">
    <thead><tr><th>Spanish</th><th>English</th></tr></thead>
    <tbody>
      <tr><td><strong>{{vocab_1_es}}</strong></td><td class="translation">{{vocab_1_en}}</td></tr>
      <tr><td><strong>{{vocab_2_es}}</strong></td><td class="translation">{{vocab_2_en}}</td></tr>
      <tr><td><strong>{{vocab_3_es}}</strong></td><td class="translation">{{vocab_3_en}}</td></tr>
      <tr><td><strong>{{vocab_4_es}}</strong></td><td class="translation">{{vocab_4_en}}</td></tr>
      <tr><td><strong>{{vocab_5_es}}</strong></td><td class="translation">{{vocab_5_en}}</td></tr>
    </tbody>
  </table>
</section>

<section class="sn-panel" id="reading">
  <h2>Reading</h2>
  <div class="example-group">
    <h3>{{section_1_heading}}</h3>
    <p>{{reading_paragraph_1}}</p>
    <p>{{reading_paragraph_2}}</p>
  </div>
  <div class="example-group">
    <h3>{{section_2_heading}}</h3>
    <p>{{reading_paragraph_3}}</p>
    <p>{{reading_paragraph_4}}</p>
  </div>
</section>

<section class="sn-panel" id="key-ideas">
  <h2>Key Ideas</h2>
  <ol class="exercise-list">
    <li>{{key_idea_1}}</li>
    <li>{{key_idea_2}}</li>
    <li>{{key_idea_3}}</li>
  </ol>
</section>

<section class="sn-panel" id="exercises">
  <h2>Exercises</h2>
  <div class="exercise-block">
    <h3 class="exercise-title">Verdadero O Falso</h3>
    <ol class="exercise-list">
      <li><details><summary>{{true_false_statement_1}}</summary><p>{{true_false_answer_1}}</p></details></li>
      <li><details><summary>{{true_false_statement_2}}</summary><p>{{true_false_answer_2}}</p></details></li>
      <li><details><summary>{{true_false_statement_3}}</summary><p>{{true_false_answer_3}}</p></details></li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Sobre La Lectura</h3>
    <ol class="exercise-list">
      <li>{{reading_question_1}}</li>
      <li>{{reading_question_2}}</li>
      <li>{{reading_question_3}}</li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Para Ti</h3>
    <ol class="exercise-list">
      <li>{{personal_question_1}}</li>
      <li>{{personal_question_2}}</li>
    </ol>
  </div>
</section>

<section class="sn-panel" id="questions">
  <h2>Questions</h2>
  <ul class="question-list">
    <li>{{question_1}}</li>
    <li>{{question_2}}</li>
    <li>{{question_3}}</li>
  </ul>
</section>

<section class="sn-panel" id="wrap-up">
  <h2>Wrap Up</h2>
  <table class="compact-table">
    <thead><tr><th>Idea</th><th>Example</th><th>Translation</th></tr></thead>
    <tbody>
      <tr><td>{{wrap_idea_1}}</td><td>{{wrap_example_1}}</td><td class="translation">{{wrap_translation_1}}</td></tr>
      <tr><td>{{wrap_idea_2}}</td><td>{{wrap_example_2}}</td><td class="translation">{{wrap_translation_2}}</td></tr>
      <tr><td>{{wrap_idea_3}}</td><td>{{wrap_example_3}}</td><td class="translation">{{wrap_translation_3}}</td></tr>
    </tbody>
  </table>
</section>
    </article>
  </div>
</div>
```
