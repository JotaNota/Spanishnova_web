# Vocabulary HTML Fragment Template

Use this template to generate clean HTML fragment files for vocabulary posts in:

`docs/content-system/generated/generated-html-posts/vocabulary/`

This is post-body HTML only. Do not include document wrappers, embedded styles, scripts, block comments, editor-specific block syntax, breadcrumbs, meta rows, or taxonomy pills.

Breadcrumbs and pills are rendered by `single-vocabulary.php` from `topic_tax` and `level_tax`.

Each vocabulary category can have 4–10 terms depending on the topic. Expand or reduce the `<li>` rows in each category to match the generated Markdown source.

## Fragment

```html
<div class="sn-lesson-wrap sn-vocab-lesson">
  <div class="sn-lesson-layout">
    <article class="sn-lesson-hero sn-vocab-paper">
      <h1>{{title}}</h1>
      <p class="sn-intro">{{intro}}</p>

      <hr style="margin: 28px 0 24px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-vocab-section" id="{{category_1_id}}" style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">{{category_1_heading}}</h2>
        <ul style="margin: 0 0 0 42px; padding: 0; display: grid; gap: 8px;">
          <li><strong>{{category_1_term_1_es}}</strong> – {{category_1_term_1_en}}</li>
          <li><strong>{{category_1_term_2_es}}</strong> – {{category_1_term_2_en}}</li>
          <li><strong>{{category_1_term_3_es}}</strong> – {{category_1_term_3_en}}</li>
          <li><strong>{{category_1_term_4_es}}</strong> – {{category_1_term_4_en}}</li>
        </ul>
      </section>

      <section class="sn-vocab-section" id="{{category_2_id}}" style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">{{category_2_heading}}</h2>
        <ul style="margin: 0 0 0 42px; padding: 0; display: grid; gap: 8px;">
          <li><strong>{{category_2_term_1_es}}</strong> – {{category_2_term_1_en}}</li>
          <li><strong>{{category_2_term_2_es}}</strong> – {{category_2_term_2_en}}</li>
          <li><strong>{{category_2_term_3_es}}</strong> – {{category_2_term_3_en}}</li>
          <li><strong>{{category_2_term_4_es}}</strong> – {{category_2_term_4_en}}</li>
        </ul>
      </section>

      <section class="sn-vocab-section" id="{{category_3_id}}" style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">{{category_3_heading}}</h2>
        <ul style="margin: 0 0 0 42px; padding: 0; display: grid; gap: 8px;">
          <li><strong>{{category_3_term_1_es}}</strong> – {{category_3_term_1_en}}</li>
          <li><strong>{{category_3_term_2_es}}</strong> – {{category_3_term_2_en}}</li>
          <li><strong>{{category_3_term_3_es}}</strong> – {{category_3_term_3_en}}</li>
          <li><strong>{{category_3_term_4_es}}</strong> – {{category_3_term_4_en}}</li>
        </ul>
      </section>

      <section class="sn-vocab-section" id="{{category_4_id}}" style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">{{category_4_heading}}</h2>
        <ul style="margin: 0 0 0 42px; padding: 0; display: grid; gap: 8px;">
          <li><strong>{{category_4_term_1_es}}</strong> – {{category_4_term_1_en}}</li>
          <li><strong>{{category_4_term_2_es}}</strong> – {{category_4_term_2_en}}</li>
          <li><strong>{{category_4_term_3_es}}</strong> – {{category_4_term_3_en}}</li>
          <li><strong>{{category_4_term_4_es}}</strong> – {{category_4_term_4_en}}</li>
        </ul>
      </section>

      <section class="sn-vocab-section" id="{{category_5_id}}" style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">{{category_5_heading}}</h2>
        <ul style="margin: 0 0 0 42px; padding: 0; display: grid; gap: 8px;">
          <li><strong>{{category_5_term_1_es}}</strong> – {{category_5_term_1_en}}</li>
          <li><strong>{{category_5_term_2_es}}</strong> – {{category_5_term_2_en}}</li>
          <li><strong>{{category_5_term_3_es}}</strong> – {{category_5_term_3_en}}</li>
          <li><strong>{{category_5_term_4_es}}</strong> – {{category_5_term_4_en}}</li>
        </ul>
      </section>

      <hr style="margin: 32px 0 24px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-vocab-section" id="common-phrases" style="margin-bottom: 30px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">{{common_phrases_heading}}</h2>
        <ul style="margin: 0 0 0 24px; padding: 0; display: grid; gap: 8px;">
          <li><strong>{{phrase_1_es}}</strong> – {{phrase_1_en}}</li>
          <li><strong>{{phrase_2_es}}</strong> – {{phrase_2_en}}</li>
          <li><strong>{{phrase_3_es}}</strong> – {{phrase_3_en}}</li>
          <li><strong>{{phrase_4_es}}</strong> – {{phrase_4_en}}</li>
        </ul>
      </section>

      <section class="sn-vocab-section" id="fun-phrase" style="margin-bottom: 34px;">
        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin-bottom: 12px;">{{fun_phrase_heading}}</h3>
        <p style="margin: 0 0 0 24px;"><strong>{{fun_phrase_es}}</strong> – {{fun_phrase_en}}</p>
      </section>

      <hr style="margin: 36px 0 28px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-vocab-section" id="exercises" style="margin-bottom: 34px;">
        <h2 style="font-size: 26px; margin-bottom: 28px;">Ejercicios</h2>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 16px;">{{translate_heading}}</h3>
        <ol style="margin: 0 0 34px 40px; padding: 0; display: grid; gap: 12px;">
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_1}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_1}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_2}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_2}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_3}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_3}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_4}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_4}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_5}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_5}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_6}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_6}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_7}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_7}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_8}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_8}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_9}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_9}}</p></details></li>
          <li><details><summary style="cursor: pointer; color: var(--sn-orange);"><span style="color: var(--sn-ink);">{{translate_prompt_10}}</span></summary><p style="margin: 8px 0 0 22px; color: var(--sn-muted);">→ {{translate_answer_10}}</p></details></li>
        </ol>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 20px; margin: 0 0 16px;">{{category_exercise_heading}}</h3>
        <p style="margin: 0 0 14px 24px;">{{category_exercise_intro}}</p>
        <ul style="margin: 0 0 0 40px; padding: 0; display: grid; gap: 12px;">
          <li><strong>{{category_exercise_1_label}}:</strong> {{category_exercise_1_body}}</li>
          <li><strong>{{category_exercise_2_label}}:</strong> {{category_exercise_2_body}}</li>
          <li><strong>{{category_exercise_3_label}}:</strong> {{category_exercise_3_body}}</li>
          <li><strong>{{category_exercise_4_label}}:</strong> {{category_exercise_4_body}}</li>
        </ul>
      </section>

      <hr style="margin: 36px 0 28px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-vocab-section" id="scenes" style="margin-bottom: 34px;">
        <h2 style="font-size: 24px; margin-bottom: 18px;">{{scenes_heading}}</h2>
        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 19px; margin: 0 0 10px;">{{scene_1_title}}</h3>
        <p style="margin-left: 24px;"><strong>{{scene_1_speaker_1}}:</strong> {{scene_1_line_1}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_1_speaker_2}}:</strong> {{scene_1_line_2}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_1_speaker_3}}:</strong> {{scene_1_line_3}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_1_speaker_4}}:</strong> {{scene_1_line_4}}</p>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 19px; margin: 24px 0 10px;">{{scene_2_title}}</h3>
        <p style="margin-left: 24px;"><strong>{{scene_2_speaker_1}}:</strong> {{scene_2_line_1}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_2_speaker_2}}:</strong> {{scene_2_line_2}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_2_speaker_3}}:</strong> {{scene_2_line_3}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_2_speaker_4}}:</strong> {{scene_2_line_4}}</p>

        <h3 style="font-family: Georgia, 'Times New Roman', serif; font-size: 19px; margin: 24px 0 10px;">{{scene_3_title}}</h3>
        <p style="margin-left: 24px;"><strong>{{scene_3_speaker_1}}:</strong> {{scene_3_line_1}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_3_speaker_2}}:</strong> {{scene_3_line_2}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_3_speaker_3}}:</strong> {{scene_3_line_3}}</p>
        <p style="margin-left: 24px;"><strong>{{scene_3_speaker_4}}:</strong> {{scene_3_line_4}}</p>
      </section>

      <hr style="margin: 36px 0 28px; border: 0; border-top: 1px solid var(--sn-border);">

      <section class="sn-vocab-section" id="questions" style="margin-bottom: 8px;">
        <h2 style="font-size: 24px; margin-bottom: 14px;">{{questions_heading}}</h2>
        <ol style="margin: 0 0 0 26px; padding: 0; display: grid; gap: 8px;">
          <li>{{question_1}}</li>
          <li>{{question_2}}</li>
          <li>{{question_3}}</li>
          <li>{{question_4}}</li>
        </ol>
      </section>
    </article>
  </div>
</div>
```
