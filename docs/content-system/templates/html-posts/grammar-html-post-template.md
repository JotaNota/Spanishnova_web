# Grammar HTML Fragment Template

Use this template to generate clean post-body HTML fragments for grammar posts in:

`docs/content-system/generated/html/grammar/`

WordPress owns the post title, metadata, navigation trail, and surrounding layout. The grammar body must not include title or metadata.

## Fragment Shape

```html
<p class="intro">{{intro}}</p>

<section id="overview">
  <h2>Overview</h2>
  <p>{{overview}}</p>
  <ul class="sentence-list">
    <li><strong>{{example_es}}</strong> &#8594; <em>{{example_en}}</em></li>
  </ul>
</section>

<section id="formas">
  <h2>Formas</h2>
  <ul class="forms-list">
    <li><strong>{{form}}</strong> &#8594; {{meaning}}</li>
  </ul>
</section>

<section id="conjugacion">
  <h2>Conjugación</h2>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Forma</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>{{subject}}</td><td>{{forma}}</td><td>{{example}}</td><td class="translation">{{translation}}</td></tr>
    </tbody>
  </table>
</section>

<section id="usos">
  <h2>Cómo/cuándo lo usamos</h2>
  <h3>{{use_heading}}</h3>
  <p>{{use_body}}</p>
  <ul class="sentence-list">
    <li><strong>{{use_example_es}}</strong> &#8594; <em>{{use_example_en}}</em></li>
  </ul>
</section>

<section id="comparacion">
  <h2>Comparación</h2>
  <table class="compact-table">
    <thead>
      <tr>
        <th>Patrón</th>
        <th>Example</th>
        <th>Translation</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>{{pattern}}</td><td>{{example}}</td><td class="translation">{{translation}}</td></tr>
    </tbody>
  </table>
</section>

<section id="oraciones">
  <h2>Oraciones.</h2>
  <h3>Afirmativas</h3>
  <ul class="sentence-list">
    <li><strong>{{affirmative_es}}</strong> &#8594; <em>{{affirmative_en}}</em></li>
  </ul>
  <h3>Negativas</h3>
  <ul class="sentence-list">
    <li><strong>{{negative_es}}</strong> &#8594; <em>{{negative_en}}</em></li>
  </ul>
  <h3>Forma de pregunta</h3>
  <ul class="question-list">
    <li><strong>{{question_es}}</strong> &#8594; <em>{{question_en}}</em></li>
  </ul>
</section>

<section id="ejercicios">
  <h2>Ejercicios</h2>
  <div class="exercise-block">
    <h3 class="exercise-title">Selección simple</h3>
    <ol class="exercise-list">
      <li><p>{{prompt}}</p><ol type="a"><li>{{option}}</li></ol><details><summary>Respuesta</summary><p>{{answer}}</p></details></li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Completar</h3>
    <ol class="exercise-list">
      <li><p>{{prompt}}</p><details><summary>Respuesta</summary><p>{{answer}}</p></details></li>
    </ol>
  </div>
  <div class="exercise-block">
    <h3 class="exercise-title">Traducción</h3>
    <ol class="exercise-list">
      <li><p>{{prompt}}</p><details><summary>Respuesta</summary><p>{{answer}}</p></details></li>
    </ol>
  </div>
</section>

<section id="wrap-up">
  <h2>Wrap Up</h2>
  <table class="compact-table">
    <thead><tr><th>Use</th><th>Example</th><th>Translation</th></tr></thead>
    <tbody><tr><td>{{use}}</td><td>{{example}}</td><td class="translation">{{translation}}</td></tr></tbody>
  </table>
</section>
```

Only include `Formas`, `Conjugación`, and `Comparación` when the lesson type and content data need them.
