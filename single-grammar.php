<?php get_header(); ?>

<main class="sn-single-page sn-grammar-page">
  <?php while (have_posts()) : the_post(); ?>
    <?php
      $grammar_terms = get_the_terms(get_the_ID(), 'grammar_tax');
      $level_terms = get_the_terms(get_the_ID(), 'level_tax');
      $topic_terms = get_the_terms(get_the_ID(), 'topic_tax');

      $grammar_terms = (!is_wp_error($grammar_terms) && $grammar_terms) ? $grammar_terms : array();
      $level_terms = (!is_wp_error($level_terms) && $level_terms) ? $level_terms : array();
      $topic_terms = (!is_wp_error($topic_terms) && $topic_terms) ? $topic_terms : array();

      $grammar_url = get_post_type_archive_link('grammar');
      $grammar_url = $grammar_url ? $grammar_url : home_url('/grammar/');

      $primary_grammar_term = null;

      foreach ($grammar_terms as $term) {
        if (!$primary_grammar_term || count(get_ancestors($term->term_id, 'grammar_tax')) > count(get_ancestors($primary_grammar_term->term_id, 'grammar_tax'))) {
          $primary_grammar_term = $term;
        }
      }

      $breadcrumb_terms = array();

      if ($primary_grammar_term) {
        $ancestor_ids = array_reverse(get_ancestors($primary_grammar_term->term_id, 'grammar_tax'));

        foreach ($ancestor_ids as $ancestor_id) {
          $ancestor = get_term($ancestor_id, 'grammar_tax');

          if ($ancestor && !is_wp_error($ancestor)) {
            $breadcrumb_terms[] = $ancestor;
          }
        }

        $breadcrumb_terms[] = $primary_grammar_term;
      }

      $topic_term_ids = wp_list_pluck($topic_terms, 'term_id');
      $level_term_ids = wp_list_pluck($level_terms, 'term_id');

      $get_topic_recommendations = static function ($post_type, $limit, $excluded_ids) use ($topic_term_ids, $level_term_ids) {
        if (!$topic_term_ids) {
          return array();
        }

        $base_args = array(
          'post_type' => $post_type,
          'post_status' => 'publish',
          'posts_per_page' => $limit,
          'post__not_in' => $excluded_ids,
          'orderby' => array(
            'menu_order' => 'ASC',
            'title' => 'ASC',
          ),
          'tax_query' => array(
            array(
              'taxonomy' => 'topic_tax',
              'field' => 'term_id',
              'terms' => $topic_term_ids,
            ),
          ),
        );

        $recommendations = array();

        if ($level_term_ids) {
          $same_level_args = $base_args;
          $same_level_args['tax_query'] = array(
            'relation' => 'AND',
            $base_args['tax_query'][0],
            array(
              'taxonomy' => 'level_tax',
              'field' => 'term_id',
              'terms' => $level_term_ids,
            ),
          );
          $same_level_query = new WP_Query($same_level_args);
          $recommendations = $same_level_query->posts;
        }

        $remaining = $limit - count($recommendations);

        if ($remaining > 0) {
          $fallback_args = $base_args;
          $fallback_args['posts_per_page'] = $remaining;
          $fallback_args['post__not_in'] = array_merge($excluded_ids, wp_list_pluck($recommendations, 'ID'));
          $fallback_query = new WP_Query($fallback_args);
          $recommendations = array_merge($recommendations, $fallback_query->posts);
        }

        return $recommendations;
      };

      $continue_practicing = $get_topic_recommendations('practice', 1, array());
      $related_lessons = $get_topic_recommendations('grammar', 3, array(get_the_ID()));
    ?>

    <div class="sn-breadcrumb">
      <a href="<?php echo esc_url($grammar_url); ?>">Grammar</a>
      <?php foreach ($breadcrumb_terms as $term) : ?>
        <?php $term_link = get_term_link($term); ?>
        <?php if (!is_wp_error($term_link)) : ?>
          <span>/</span>
          <a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <div class="sn-meta-row">
      <span class="sn-pill"><a href="<?php echo esc_url($grammar_url); ?>">Grammar</a></span>
      <?php foreach ($level_terms as $term) : ?>
        <?php $term_link = get_term_link($term); ?>
        <?php if (!is_wp_error($term_link)) : ?>
          <span class="sn-pill"><a href="<?php echo esc_url($term_link); ?>"><?php echo esc_html($term->name); ?></a></span>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>

    <h1 class="sn-post-title"><?php the_title(); ?></h1>

    <div class="sn-post-content">
      <?php the_content(); ?>
    </div>

    <?php if ($continue_practicing || $related_lessons) : ?>
      <aside class="sn-grammar-recommendations" aria-label="Grammar recommendations">
        <?php if ($continue_practicing) : ?>
          <section class="sn-recommendation-section">
            <h2>Continue practicing</h2>
            <div class="sn-recommendation-list">
              <?php foreach ($continue_practicing as $recommendation) : ?>
                <article class="sn-recommendation-item">
                  <h3><a href="<?php echo esc_url(get_permalink($recommendation)); ?>"><?php echo esc_html(get_the_title($recommendation)); ?></a></h3>
                  <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt($recommendation->ID), 24)); ?></p>
                  <a class="sn-recommendation-link" href="<?php echo esc_url(get_permalink($recommendation)); ?>">View practice</a>
                </article>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>

        <?php if ($related_lessons) : ?>
          <section class="sn-recommendation-section">
            <h2>Related lessons</h2>
            <div class="sn-recommendation-list">
              <?php foreach ($related_lessons as $recommendation) : ?>
                <article class="sn-recommendation-item">
                  <h3><a href="<?php echo esc_url(get_permalink($recommendation)); ?>"><?php echo esc_html(get_the_title($recommendation)); ?></a></h3>
                  <p><?php echo esc_html(wp_trim_words(spanishnova_get_card_excerpt($recommendation->ID), 24)); ?></p>
                  <a class="sn-recommendation-link" href="<?php echo esc_url(get_permalink($recommendation)); ?>">View lesson</a>
                </article>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>

        <a class="sn-grammar-archive-link" href="<?php echo esc_url(home_url('/grammar/')); ?>">Browse all grammar lessons</a>
      </aside>
    <?php endif; ?>
  <?php endwhile; ?>
</main>

<?php get_footer(); ?>
