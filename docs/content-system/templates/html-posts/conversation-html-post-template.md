# Conversation HTML Fragment Template

Use this template to generate clean HTML fragment files for conversation posts in:

`docs/content-system/generated/generated-html-posts/conversations/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, editor-specific block syntax, breadcrumb, taxonomy pills, or metadata rows.

## Fragment

```html
<div class="sn-lesson-wrap sn-conversation-lesson">
  <div class="sn-lesson-layout">
    <article class="sn-lesson-hero sn-conversation-paper">
      <h1>{{title}}</h1>
      <p class="sn-intro">{{intro}}</p>

      <section class="sn-conversation-section" id="vocabulario">
        <h2>Vocabulario</h2>
        <div class="sn-conversation-block">
          <ul class="sn-conversation-vocab-list">
            <li><strong>{{vocab_1_es}}</strong> – {{vocab_1_en}}</li>
            <li><strong>{{vocab_2_es}}</strong> – {{vocab_2_en}}</li>
            <li><strong>{{vocab_3_es}}</strong> – {{vocab_3_en}}</li>
            <li><strong>{{vocab_4_es}}</strong> – {{vocab_4_en}}</li>
            <li><strong>{{vocab_5_es}}</strong> – {{vocab_5_en}}</li>
          </ul>
        </div>
      </section>

      <section class="sn-conversation-section" id="conversacion">
        <h2>Conversación</h2>
        <div class="sn-conversation-dialogue">
          {{conversation_html}}
        </div>
      </section>

      <section class="sn-conversation-section" id="frases-utiles">
        <h2>Frases útiles</h2>
        <div class="sn-conversation-block">
          <ul class="sn-conversation-list">
            <li><strong>{{phrase_1_es}}</strong> <span>{{phrase_1_en}}</span></li>
            <li><strong>{{phrase_2_es}}</strong> <span>{{phrase_2_en}}</span></li>
            <li><strong>{{phrase_3_es}}</strong> <span>{{phrase_3_en}}</span></li>
          </ul>
        </div>
      </section>

      <section class="sn-conversation-section" id="practica">
        <h2>Práctica</h2>
        <div class="sn-conversation-block">
          <h3>Traduce</h3>
          <ol class="sn-conversation-list">
            <li><details><summary>{{translate_prompt_1}}</summary><p>{{translate_answer_1}}</p></details></li>
            <li><details><summary>{{translate_prompt_2}}</summary><p>{{translate_answer_2}}</p></details></li>
            <li><details><summary>{{translate_prompt_3}}</summary><p>{{translate_answer_3}}</p></details></li>
            <li><details><summary>{{translate_prompt_4}}</summary><p>{{translate_answer_4}}</p></details></li>
            <li><details><summary>{{translate_prompt_5}}</summary><p>{{translate_answer_5}}</p></details></li>
            <li><details><summary>{{translate_prompt_6}}</summary><p>{{translate_answer_6}}</p></details></li>
          </ol>
        </div>
      </section>

      <section class="sn-conversation-section" id="situacion">
        <h2>Situación</h2>
        <div class="sn-conversation-block">
          <p class="sn-conversation-instruction">Usa esta situación para practicar la conversación con otra persona. Cambia los detalles según tus gustos.</p>
          <ol class="sn-conversation-list">
            <li>{{roleplay_1}}</li>
            <li>{{roleplay_2}}</li>
            <li>{{roleplay_3}}</li>
          </ol>
        </div>
      </section>

      <section class="sn-conversation-section" id="para-conversar">
        <h2>Para conversar</h2>
        <div class="sn-conversation-block">
          <ul class="sn-conversation-list">
            <li>{{discussion_1}}</li>
            <li>{{discussion_2}}</li>
            <li>{{discussion_3}}</li>
          </ul>
        </div>
      </section>
    </article>
  </div>
</div>
```

## Conversation body rules

Generate `{{conversation_html}}` from the selected `default_variant`.

For `podcast_style`, use dialogue lines inside one continuous dialogue block.

For `street_interview`, use interview headings and grouped dialogue blocks.

Use semantic HTML only. Do not add inline styles. Do not add metadata, breadcrumb, pills, or WordPress wrappers.

Use short indented italic notes for selected English support. Do not use a box for translation support.
