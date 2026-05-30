# Reading HTML Fragment Template

Use this template to generate clean HTML fragment files for reading posts in:

`docs/content-system/generated/generated-html-posts/readings/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, editor-specific block syntax, breadcrumbs, meta rows, taxonomy pills, or `sn-panel` section cards.

Breadcrumbs and pills are rendered by the readings single template from `topic_tax` and `level_tax`.

Use `reading_variant` only to shape the content before HTML generation. Do not print `reading_variant` in the HTML.

The HTML must follow the generated Markdown source and the selected structure in `docs/content-system/templates/reading-structures/reading-structure-variants.md`.

## Fragment Pattern

```html
<div class="sn-lesson-wrap sn-reading-lesson">
  <div class="sn-lesson-layout">
    <article class="sn-lesson-hero sn-reading-paper">
      <h1>{{title}}</h1>
      <p class="sn-intro">{{brief_english_intro}}</p>

      <hr>

      <section class="sn-reading-section" id="vocabulario">
        <h2>Vocabulario</h2>
        <ul class="sn-reading-list">
          <li><strong>{{term_es}}</strong> – {{term_en}}</li>
        </ul>
      </section>

      <hr>

      <section class="sn-reading-section" id="lectura">
        <h2>Lectura</h2>

        <section class="sn-reading-block">
          <h3>{{section_heading}}</h3>
          <div class="sn-reading-copy">
            <p>{{paragraph}}</p>
          </div>
        </section>
      </section>

      <hr>

      <section class="sn-reading-section" id="ideas-clave">
        <h2>Ideas clave</h2>
        <ul class="sn-reading-list">
          <li>{{key_idea}}</li>
        </ul>
      </section>

      <hr>

      <section class="sn-reading-section" id="actividad">
        <h2>Actividad</h2>

        <h3>Verdadero o falso</h3>
        <ol class="sn-reading-list">
          <li>
            <details>
              <summary>{{true_false_statement}}</summary>
              <p>→ {{true_false_answer}}</p>
            </details>
          </li>
        </ol>

        <h3>Sobre la lectura</h3>
        <ol class="sn-reading-list">
          <li>{{reading_question}}</li>
        </ol>

        <h3>Para ti</h3>
        <ol class="sn-reading-list">
          <li>{{personal_question}}</li>
        </ol>
      </section>
    </article>
  </div>
</div>
```

## Generation Rules

- Repeat list items, reading blocks, paragraphs, grouped items, and exercises according to the generated Markdown source.
- Keep the outer wrapper exactly as shown.
- Use `<hr>` between major blocks.
- Use `<details>` only when the generated Markdown includes answers to reveal.
- Use visible headings in Spanish.
- For `procedural_set`, render each grouped item as a repeated `sn-reading-block` with parallel subheadings.
- For `curiosity_article`, render thematic sections as repeated `sn-reading-block` sections.
- Do not include inline `style` attributes.
- Do not include `sn-breadcrumb`, `sn-meta-row`, `sn-pill`, or `sn-panel`.
