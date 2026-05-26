<?php get_header(); ?>
<main>
  <section class="hero">
    <form class="search-bar" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input type="search" name="s" placeholder="Search anything..." value="<?php echo get_search_query(); ?>">
      <button class="search-icon" type="submit" aria-label="Search">⌕</button>
    </form>
    <div class="eyebrow">Simple Spanish.</div>
    <h1>Practice Spanish with useful lessons, easy readings, and real examples.</h1>
  </section>

  <section class="content-layout">
    <div class="panel">
      <h2>Featured activities</h2>
      <div class="activity-list">
        <a class="activity-row" href="<?php echo esc_url(home_url('/grammar/ser-in-the-present-tense/')); ?>">
          <span class="label">Grammar</span>
          <div><h3>Ser: basic uses and examples</h3><p>Identity, origin, profession, and descriptions.</p></div>
          <span class="arrow">→</span>
        </a>
        <a class="activity-row" href="#">
          <span class="label">Reading</span>
          <div><h3>Mi nuevo barrio</h3><p>A beginner reading with vocabulary and optional audio.</p></div>
          <span class="arrow">→</span>
        </a>
        <a class="activity-row" href="#">
          <span class="label">Vocabulary</span>
          <div><h3>Food vocabulary in Spanish</h3><p>Common foods, restaurant phrases, and practice sentences.</p></div>
          <span class="arrow">→</span>
        </a>
        <a class="activity-row" href="#">
          <span class="label">Practice</span>
          <div><h3>Ser practice</h3><p>Clickable exercises with answer reveals and a worksheet.</p></div>
          <span class="arrow">→</span>
        </a>
        <a class="activity-row" href="#">
          <span class="label">Conversation</span>
          <div><h3>At the coffee shop</h3><p>A short dialogue with useful phrases and vocabulary.</p></div>
          <span class="arrow">→</span>
        </a>
        <a class="activity-row" href="#">
          <span class="label">Worksheet</span>
          <div><h3>Present tense worksheet</h3><p>Practice regular verbs with answers included.</p></div>
          <span class="arrow">→</span>
        </a>
      </div>
      <div class="pagination"><a class="active" href="#">1</a><a href="#">2</a><a href="#">3</a><a href="#">→</a></div>
    </div>

    <aside>
      <div class="panel side-card">
        <h2>Resources</h2>
        <ul>
          <li>Beginner grammar checklist</li>
          <li>Vocabulary PDFs</li>
          <li>Reading PDFs</li>
          <li>Worksheets</li>
        </ul>
      </div>
      <div class="panel side-card">
        <h2>Level</h2>
        <ul>
          <li>Beginner</li>
          <li>Intermediate</li>
          <li>Advanced</li>
        </ul>
      </div>
    </aside>
  </section>

  <section class="category-strip">
    <a class="category-card" href="<?php echo esc_url(home_url('/grammar/')); ?>"><h3>Grammar</h3><p>Verb tenses, pronouns, adjectives, and sentence structure.</p></a>
    <a class="category-card" href="<?php echo esc_url(home_url('/vocabulary/')); ?>"><h3>Vocabulary</h3><p>Useful words and phrases organized by topic.</p></a>
    <a class="category-card" href="<?php echo esc_url(home_url('/readings/')); ?>"><h3>Readings</h3><p>Different types of readings for practice and comprehension.</p></a>
    <a class="category-card" href="<?php echo esc_url(home_url('/conversations/')); ?>"><h3>Conversations</h3><p>Dialogues, interviews, and everyday conversations.</p></a>
    <a class="category-card" href="<?php echo esc_url(home_url('/practice/')); ?>"><h3>Practice</h3><p>Exercises, answer reveals, and downloadable worksheets.</p></a>
  </section>

  <section class="audience-box">
    <div><h2>For students</h2></div>
    <div><h2>For teachers</h2></div>
  </section>

  <section class="support" id="donate">
    <div>
      <h2>Support SpanishNova.</h2>
      <p>Help support lessons, readings, worksheets, audio, and future learning content.</p>
    </div>
    <a class="btn btn-primary" href="<?php echo esc_url(home_url('/donate/')); ?>">Donate</a>
  </section>
</main>
<?php get_footer(); ?>
