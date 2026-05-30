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

    <section class="sn-conversation-section" id="useful-vocabulary">
      <h2>Useful Vocabulary</h2>
      <div class="sn-conversation-block">
        <table class="sn-conversation-table">
          <thead>
            <tr>
              <th>Spanish</th>
              <th>English</th>
            </tr>
          </thead>
          <tbody>
            <tr><td><strong>{{vocab_1_es}}</strong></td><td>{{vocab_1_en}}</td></tr>
            <tr><td><strong>{{vocab_2_es}}</strong></td><td>{{vocab_2_en}}</td></tr>
            <tr><td><strong>{{vocab_3_es}}</strong></td><td>{{vocab_3_en}}</td></tr>
            <tr><td><strong>{{vocab_4_es}}</strong></td><td>{{vocab_4_en}}</td></tr>
            <tr><td><strong>{{vocab_5_es}}</strong></td><td>{{vocab_5_en}}</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <section class="sn-conversation-section" id="conversation">
      <h2>Conversation</h2>
      <div class="sn-conversation-dialogue">
        {{conversation_html}}
      </div>
    </section>

    <section class="sn-conversation-section" id="useful-phrases">
      <h2>Useful Phrases</h2>
      <div class="sn-conversation-block">
        <ul class="sn-conversation-list">
          <li><strong>{{phrase_1_es}}</strong> <span>{{phrase_1_en}}</span></li>
          <li><strong>{{phrase_2_es}}</strong> <span>{{phrase_2_en}}</span></li>
          <li><strong>{{phrase_3_es}}</strong> <span>{{phrase_3_en}}</span></li>
        </ul>
      </div>
    </section>

    <section class="sn-conversation-section" id="practice">
      <h2>Practice</h2>
      <div class="sn-conversation-block">
        <h3>Translate</h3>
        <ol class="sn-conversation-list">
          <li><details><summary>{{translate_prompt_1}}</summary><p>{{translate_answer_1}}</p></details></li>
          <li><details><summary>{{translate_prompt_2}}</summary><p>{{translate_answer_2}}</p></details></li>
          <li><details><summary>{{translate_prompt_3}}</summary><p>{{translate_answer_3}}</p></details></li>
        </ol>
      </div>
      <div class="sn-conversation-block">
        <h3>Complete the Conversation</h3>
        <ol class="sn-conversation-list">
          <li>{{complete_1}}</li>
          <li>{{complete_2}}</li>
        </ol>
      </div>
    </section>

    <section class="sn-conversation-section" id="roleplay">
      <h2>Roleplay</h2>
      <div class="sn-conversation-block">
        <ol class="sn-conversation-list">
          <li>{{roleplay_1}}</li>
          <li>{{roleplay_2}}</li>
          <li>{{roleplay_3}}</li>
        </ol>
      </div>
    </section>

    <section class="sn-conversation-section" id="discussion">
      <h2>Discussion</h2>
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

Generate `{{conversation_html}}` from the selected `conversation_variant`.

For `podcast_style`, use dialogue lines inside one continuous dialogue block.

For `street_interview`, use interview headings and grouped dialogue blocks.

Use semantic HTML only. Do not add inline styles. Do not add metadata, breadcrumb, pills, or WordPress wrappers.
