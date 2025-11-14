<?php get_header(); ?>

<main class="min-h-screen">
  <section class="container mx-auto px-6 py-16 max-w-7xl">
    <h1 class="serif text-[3rem] font-[900] text-gray-900 mb-4">Статьи</h1>
    <p class="text-[1.125rem] text-gray-600 mb-12 max-w-3xl">
      Подборка статей о христианской жизни, вере и практике.
    </p>
    <?php
      $terms = get_terms([
        'taxonomy' => 'article_category',
        'hide_empty' => true,
      ]);
      $qo = get_queried_object();
      $active_term_id = (is_tax('article_category') && isset($qo->term_id)) ? (int)$qo->term_id : 0;
      if (!is_wp_error($terms) && $terms):
    ?>
      <div class="flex flex-wrap gap-2 mb-10" data-article-filter>
        <?php
          $base_cls = 'px-4 py-2 rounded-full transition-all';
          $active = ' bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]';
          $inactive = ' text-gray-700 hover:bg-gray-100';
          $all_active = $active_term_id === 0;
        ?>
        <button type="button" data-filter="all" class="<?php echo $base_cls . ($all_active ? $active : $inactive); ?>" aria-pressed="<?php echo $all_active ? 'true' : 'false'; ?>">Все</button>
        <?php foreach ($terms as $t):
          $is_active = $active_term_id === (int)$t->term_id;
        ?>
          <button type="button" data-filter="<?php echo (int)$t->term_id; ?>" class="<?php echo $base_cls . ($is_active ? $active : $inactive); ?>" aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"><?php echo esc_html($t->name); ?></button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (have_posts()): ?>
      <?php global $wp_query; $initial_count = (int)$wp_query->post_count; $initial_term = $active_term_id ? (string)$active_term_id : 'all'; ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" data-article-list data-article-ppp="12" data-article-offset="<?php echo $initial_count; ?>" data-article-term="<?php echo esc_attr($initial_term); ?>">
        <?php while (have_posts()): the_post(); ?>
          <?php
            $term_ids = wp_get_object_terms(get_the_ID(), 'article_category', ['fields' => 'ids']);
            if (is_wp_error($term_ids)) { $term_ids = []; }
            $terms_attr = $term_ids ? implode(',', array_map('intval', $term_ids)) : '';
          ?>
          <a href="<?php the_permalink(); ?>" data-article-card data-terms="<?php echo esc_attr($terms_attr); ?>" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
            <div class="relative">
              <div class="absolute top-3 left-3 bg-white/95 px-3 py-1 rounded-full text-[0.75rem] text-gray-700 z-10">Article</div>
              <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                <?php
                  if (has_post_thumbnail()) {
                    the_post_thumbnail('article_card', ['class' => 'w-full h-full object-cover']);
                  } else {
                    $ph_file = get_template_directory() . '/assets/images/article_placeholder.png';
                    if (file_exists($ph_file)) {
                      $ph_url = get_template_directory_uri() . '/assets/images/article_placeholder.png?ver=' . filemtime($ph_file);
                      echo '<img src="' . esc_url($ph_url) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover" loading="lazy" decoding="async" />';
                    } else {
                      $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500"><rect width="100%" height="100%" fill="#e5e7eb"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="sans-serif" font-size="20">Нет обложки</text></svg>';
                      $data_url = 'data:image/svg+xml;base64,' . base64_encode($svg);
                      echo '<img src="' . $data_url . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover" />';
                    }
                  }
                ?>
              </div>
            </div>
            <div class="p-6">
              <h3 class="serif text-[1.25rem] font-[700] text-gray-900 mb-2 line-clamp-2"><?php the_title(); ?></h3>
              <p class="text-gray-600 mb-4 line-clamp-2"><?php echo esc_html( get_post_meta(get_the_ID(), 'zdravoe_article_description', true) ?: get_the_excerpt() ); ?></p>
              <div class="flex items-center gap-3 pt-4 border-t border-gray-100 text-sm text-gray-500">
                <?php 
                  echo get_the_date('j F Y'); 
                  $custom = get_post_meta(get_the_ID(), 'zdravoe_article_content', true);
                  $source = $custom ? $custom : get_post_field('post_content', get_the_ID());
                  $rt = function_exists('zdravoe_reading_time') ? zdravoe_reading_time($source) : ['minutes'=>0,'words'=>0];
                ?>
                <span class="mx-1">·</span>
                <span class="inline-flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  ~<?php echo (int)$rt['minutes']; ?> мин
                </span>
              </div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
      <p class="text-gray-600 mt-8 hidden" data-article-empty>Нет статей по выбранной категории на этой странице.</p>
      <div data-article-sentinel class="h-12"></div>
    <?php else: ?>
      <p class="text-gray-600">Пока нет статей.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
