# Reading HTML Fragment Template

Use this template to generate clean HTML fragment files for reading posts in:

`docs/content-system/generated/generated-html-posts/readings/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, editor-specific block syntax, breadcrumbs, meta rows, taxonomy pills, or `sn-panel` section cards.

Breadcrumbs and pills must be rendered by the readings single template from `topic_tax` and `level_tax`, not hardcoded inside generated HTML.

Use `reading_variant` only to shape the content before HTML generation. Do not print `reading_variant` in the HTML.

The HTML must follow the generated Markdown source. Add, remove, or repeat reading sections according to the selected structure in `docs/content-system/templates/reading-structures/reading-structure-variants.md`.

## Fragment

```html
<div class="sn-lesson-wrap sn-reading-lesson">
  <div class="sn-lesson-layout">
    <article class="sn-lesson-hero sn-reading-paper">
      <h1>{{title}}</h1>
      <p class="sn-intro">{{brief_english_intro}}</p>

      <hr style="margin: 28px 0 24px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-reading-section" id="vocabulario" style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">Vocabulario</h2>
        <ul style="margin: 0 0 0 42px; padding: 0; display: grid; gap: 8px;">
          <li><strong>{{term_1_es}}</strong> – {{term_1_en}}</li>
          <li><strong>{{term_2_es}}</strong> – {{term_2_en}}</li>
          <li><strong>{{term_3_es}}</strong> – {{term_3_en}}</li>
          <li><strong>{{term_4_es}}</strong> – {{term_4_en}}</li>
          <li><strong>{{term_5_es}}</strong> – {{term_5_en}}</li>
          <li><strong>{{term_6_es}}</strong> – {{term_6_en}}</li>
        </ul>
      </section>

      <hr style="margin: 32px 0 24px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-reading-section" id="lectura" style="margin-bottom: 34px;">
        <h2 style="font-size: 26px; margin-bottom: 22px;">Lectura</h2>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 12px;">{{section_1_heading}}</h3>
        <div style="margin: 0 0 26px 24px; display: grid; gap: 12px;">
          <p style="margin: 0;">{{section_1_paragraph_1}}</p>
          <p style="margin: 0;">{{section_1_paragraph_2}}</p>
        </div>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 12px;">{{section_2_heading}}</h3>
        <div style="margin: 0 0 26px 24px; display: grid; gap: 12px;">
          <p style="margin: 0;">{{section_2_paragraph_1}}</p>
          <p style="margin: 0;">{{section_2_paragraph_2}}</p>
        </div>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 12px;">{{section_3_heading}}</h3>
        <div style="margin: 0 0 26px 24px; display: grid; gap: 12px;">
          <p style="margin: 0;">{{section_3_paragraph_1}}</p>
          <p style="margin: 0;">{{section_3_paragraph_2}}</p>
        </div>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 12px;">{{section_4_heading}}</h3>
        <div style="margin: 0 0 0 24px; display: grid; gap: 12px;">
          <p style="margin: 0;">{{section_4_paragraph_1}}</p>
          <p style="margin: 0;">{{section_4_paragraph_2}}</p>
        </div>
      </section>

      <hr style="margin: 36px 0 28px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-reading-section" id="ideas-clave" style="margin-bottom: 34px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">Ideas clave</h2>
        <ul style="margin: 0 0 0 42px; padding: 0; display: grid; gap: 8px;">
          <li>{{key_idea_1}}</li>
          <li>{{key_idea_2}}</li>
          <li>{{key_idea_3}}</li>
          <li>{{key_idea_4}}</li>
          <li>{{key_idea_5}}</li>
        </ul>
      </section>

      <hr style="margin: 36px 0 28px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-reading-section" id="actividad" style="margin-bottom: 8px;">
        <h2 style="font-size: 26px; margin-bottom: 28px;">Actividad</h2>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 16px;">Verdadero o falso</h3>
        <ol style="margin: 0 0 34px 40px; padding: 0; display: grid; gap: 12px;">
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{true_false_statement_1}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{true_false_answer_1}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{true_false_statement_2}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{true_false_answer_2}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{true_false_statement_3}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{true_false_answer_3}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{true_false_statement_4}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{true_false_answer_4}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{true_false_statement_5}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{true_false_answer_5}}</p></details></li>
        </ol>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 16px;">Sobre la lectura</h3>
        <ol style="margin: 0 0 34px 40px; padding: 0; display: grid; gap: 12px;">
          <li>{{reading_question_1}}</li>
          <li>{{reading_question_2}}</li>
          <li>{{reading_question_3}}</li>
          <li>{{reading_question_4}}</li>
        </ol>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 16px;">Para ti</h3>
        <ol style="margin: 0 0 0 40px; padding: 0; display: grid; gap: 12px;">
          <li>{{personal_question_1}}</li>
          <li>{{personal_question_2}}</li>
        </ol>
      </section>
    </article>
  </div>
</div>
```

## Rules

- Do not include `sn-breadcrumb`, `sn-meta-row`, or `sn-pill`.
- Do not use `sn-panel`.
- Use one central paper layout: `sn-lesson-wrap sn-reading-lesson`, `sn-lesson-layout`, `sn-lesson-hero sn-reading-paper`.
- Use visible headings in Spanish.
- Use `<hr>` between major blocks.
- Use `<details>` for true/false answers.
- Follow the generated Markdown source. The number of reading sections, vocabulary terms, grouped items, or exercises may vary by `reading_variant`.
- For `procedural_set`, adapt the reading body into grouped items with parallel subheadings such as `Qué lleva` and `Preparación`.
- For `curiosity_article`, adapt the reading body into thematic sections and avoid repeated headings.
