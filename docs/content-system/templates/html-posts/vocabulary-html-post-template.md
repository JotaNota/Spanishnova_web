# Vocabulary HTML Fragment Template

Use this template to generate clean HTML fragment files for vocabulary posts in:

`docs/content-system/generated/generated-html-posts/vocabulary/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, or editor-specific block syntax.

## Fragment

```html
<div class="sn-lesson-wrap">
  <div class="sn-lesson-layout">
    <article class="sn-lesson-article">
<section class="sn-lesson-hero">
  <div class="sn-breadcrumb">Vocabulary / {{topic_tax}} / {{title_base}}</div>
  <div class="sn-meta-row">
    <span class="sn-pill">Vocabulary</span>
    <span class="sn-pill">{{level_tax}}</span>
    <span class="sn-pill">{{topic_tax}}</span>
  </div>
  <h1>{{title}}</h1>
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

<section class="sn-panel" id="vocabulary">
  <h2>Vocabulary</h2>
  <div class="two-column">
    <div class="example-group">
      <h3>{{category_1_heading}}</h3>
      <table class="compact-table">
        <thead><tr><th>Spanish</th><th>English</th></tr></thead>
        <tbody>
          <tr><td><strong>{{vocab_1_es}}</strong></td><td class="translation">{{vocab_1_en}}</td></tr>
          <tr><td><strong>{{vocab_2_es}}</strong></td><td class="translation">{{vocab_2_en}}</td></tr>
          <tr><td><strong>{{vocab_3_es}}</strong></td><td class="translation">{{vocab_3_en}}</td></tr>
          <tr><td><strong>{{vocab_4_es}}</strong></td><td class="translation">{{vocab_4_en}}</td></tr>
        </tbody>
      </table>
    </div>
    <div class="example-group">
      <h3>{{category_2_heading}}</h3>
      <table class="compact-table">
        <thead><tr><th>Spanish</th><th>English</th></tr></thead>
        <tbody>
          <tr><td><strong>{{vocab_5_es}}</strong></td><td class="translation">{{vocab_5_en}}</td></tr>
          <tr><td><strong>{{vocab_6_es}}</strong></td><td class="translation">{{vocab_6_en}}</td></tr>
          <tr><td><strong>{{vocab_7_es}}</strong></td><td class="translation">{{vocab_7_en}}</td></tr>
          <tr><td><strong>{{vocab_8_es}}</strong></td><td class="translation">{{vocab_8_en}}</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="sn-panel" id="common-phrases">
  <h2>Common Phrases</h2>
  <ul class="sentence-list">
    <li><strong>{{phrase_1_es}}</strong> <span class="translation">{{phrase_1_en}}</span></li>
    <li><strong>{{phrase_2_es}}</strong> <span class="translation">{{phrase_2_en}}</span></li>
    <li><strong>{{phrase_3_es}}</strong> <span class="translation">{{phrase_3_en}}</span></li>
    <li><strong>{{phrase_4_es}}</strong> <span class="translation">{{phrase_4_en}}</span></li>
  </ul>
</section>

<section class="sn-panel" id="dialogue">
  <h2>Dialogue</h2>
  <div class="example-bubble">
    <h3>{{dialogue_scene}}</h3>
    <p><strong>{{speaker_1}}:</strong> {{dialogue_line_1}}</p>
    <p><strong>{{speaker_2}}:</strong> {{dialogue_line_2}}</p>
    <p><strong>{{speaker_1}}:</strong> {{dialogue_line_3}}</p>
    <p><strong>{{speaker_2}}:</strong> {{dialogue_line_4}}</p>
  </div>
</section>

<section class="sn-panel" id="exercises">
  <h2>Exercises</h2>
  <div class="exercise-block">
    <h3 class="exercise-title">Translate</h3>
    <ol class="exercise-list">
      <li><details><summary>{{translate_prompt_1}}</summary><p>{{translate_answer_1}}</p></details></li>
      <li><details><summary>{{translate_prompt_2}}</summary><p>{{translate_answer_2}}</p></details></li>
      <li><details><summary>{{translate_prompt_3}}</summary><p>{{translate_answer_3}}</p></details></li>
      <li><details><summary>{{translate_prompt_4}}</summary><p>{{translate_answer_4}}</p></details></li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Write Or Speak</h3>
    <ol class="exercise-list">
      <li>{{write_speak_1}}</li>
      <li>{{write_speak_2}}</li>
      <li>{{write_speak_3}}</li>
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
    <thead><tr><th>Use</th><th>Example</th><th>Translation</th></tr></thead>
    <tbody>
      <tr><td>{{wrap_use_1}}</td><td>{{wrap_example_1}}</td><td class="translation">{{wrap_translation_1}}</td></tr>
      <tr><td>{{wrap_use_2}}</td><td>{{wrap_example_2}}</td><td class="translation">{{wrap_translation_2}}</td></tr>
      <tr><td>{{wrap_use_3}}</td><td>{{wrap_example_3}}</td><td class="translation">{{wrap_translation_3}}</td></tr>
    </tbody>
  </table>
</section>
    </article>
  </div>
</div>
```
