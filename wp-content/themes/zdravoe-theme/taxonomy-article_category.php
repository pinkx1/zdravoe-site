<?php get_header(); ?>

<main class="min-h-screen">
  <section class="container mx-auto px-6 py-16 max-w-7xl">
    <?php
      $term = get_queried_object();

      $articles_q = new WP_Query([
        'post_type'      => 'article',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => [[
          'taxonomy' => 'article_category',
          'field'    => 'term_id',
          'terms'    => [$term->term_id],
        ]],
        'orderby'        => 'date',
        'order'          => 'ASC',
      ]);

      $articles = [];
      if ($articles_q->have_posts()) {
        while ($articles_q->have_posts()) {
          $articles_q->the_post();
          $custom  = get_post_meta(get_the_ID(), 'zdravoe_article_content', true);
          $raw     = $custom ? $custom : get_post_field('post_content', get_the_ID());
          $content = $raw ? apply_filters('the_content', $raw) : '';
          $articles[] = [
            'id'      => get_the_ID(),
            'title'   => get_the_title(),
            'content' => $content,
            'link'    => get_permalink(),
          ];
        }
        wp_reset_postdata();
      }

      $total = count($articles);
      $current_index = isset($_GET['a']) ? max(1, (int) $_GET['a']) : 1;
      if ($current_index > $total) {
        $current_index = $total;
      }
      $current_article = $total > 0 ? $articles[$current_index - 1] : null;

      $permalink = get_term_link($term);
      $prev_url = ($current_index > 1) ? add_query_arg('a', $current_index - 1, $permalink) : '';
      $next_url = ($current_index < $total) ? add_query_arg('a', $current_index + 1, $permalink) : '';
    ?>

    <a class="inline-flex items-center gap-2 text-[#8A6A5B] hover:text-[#7A5C4E] transition-colors mb-8" href="<?php echo esc_url( get_post_type_archive_link('article') ); ?>">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5" aria-hidden="true"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
      Все тематические сборники статей
    </a>

    <div class="grid lg:grid-cols-[320px_1fr] gap-12">
      <aside class="space-y-6 min-w-0">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <h1 class="serif text-[1.75rem] md:text-[2rem] font-[800] text-gray-900 mb-3 leading-tight">
            <?php echo esc_html($term->name); ?>
          </h1>
          <?php if (!empty($term->description)) : ?>
            <p class="text-gray-700 text-sm leading-relaxed mb-4">
              <?php echo esc_html($term->description); ?>
            </p>
          <?php endif; ?>
          <p class="text-xs uppercase tracking-wide text-gray-500">
            <?php echo (int) $total; ?> статей в этом сборнике
          </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <h2 class="serif text-[1.25rem] font-[700] text-gray-900 mb-4">Статьи сборника</h2>
          <nav class="space-y-2" aria-label="Статьи сборника">
            <?php if (!empty($articles)) : ?>
              <?php foreach ($articles as $idx => $article) :
                $is_current = ($idx + 1) === $current_index;
                $url = add_query_arg('a', $idx + 1, $permalink);
                $cls = 'block w-full text-left px-4 py-2.5 rounded-lg transition-colors text-sm ' . ($is_current ? 'bg-[#FAF8F5] text-[#8A6A5B]' : 'hover:bg-[#FAF8F5] text-gray-700 hover:text-[#8A6A5B]');
              ?>
                <a
                  href="<?php echo esc_url($url); ?>"
                  class="<?php echo esc_attr($cls); ?>"
                  <?php echo $is_current ? 'aria-current="page"' : ''; ?>
                >
                  <?php echo esc_html($article['title']); ?>
                </a>
              <?php endforeach; ?>
            <?php else : ?>
              <p class="text-gray-500 text-sm">В этом сборнике пока нет статей.</p>
            <?php endif; ?>
          </nav>
        </div>
      </aside>

      <main class="bg-white rounded-xl shadow-sm p-6 md:p-10 lg:p-12 min-w-0">
        <?php if ($current_article) : ?>
          <h2 class="serif break-words text-2xl sm:text-3xl md:text-[1.9rem] font-[800] text-gray-900 mb-6 leading-tight">
            <?php echo esc_html($current_article['title']); ?>
          </h2>
          <div class="prose prose-lg max-w-none break-words">
            <div class="text-gray-700 leading-[1.7] mb-6">
              <?php echo $current_article['content']; ?>
            </div>
          </div>

          <div class="flex gap-4 mt-10 pt-6 border-t border-gray-200">
            <a
              href="<?php echo $prev_url ? esc_url($prev_url) : '#'; ?>"
              class="flex-1 bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-full hover:bg-gray-50 transition-colors inline-flex items-center justify-center gap-2 <?php echo $current_index <= 1 ? 'pointer-events-none opacity-40' : ''; ?>"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
              Предыдущая статья
            </a>
            <a
              href="<?php echo $next_url ? esc_url($next_url) : '#'; ?>"
              class="flex-1 bg-[#8A6A5B] text-white px-6 py-3 rounded-full hover:bg-[#7A5C4E] transition-colors inline-flex items-center justify-center gap-2 <?php echo $current_index >= $total ? 'pointer-events-none opacity-40' : ''; ?>"
            >
              Следующая статья
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
            </a>
          </div>
        <?php else : ?>
          <h2 class="serif text-2xl font-[800] text-gray-900 mb-4">В этом сборнике пока нет статей</h2>
          <p class="text-gray-700">Пожалуйста, зайдите позже — здесь появятся новые материалы.</p>
        <?php endif; ?>
      </main>
    </div>
  </section>
</main>

<?php get_footer(); ?>
