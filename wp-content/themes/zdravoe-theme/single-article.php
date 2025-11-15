<?php get_header(); ?>

<main class="min-h-screen">
  <section class="container mx-auto px-6 py-16 max-w-7xl">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <?php
        $adesc = get_post_meta(get_the_ID(), 'zdravoe_article_description', true);
        $custom = get_post_meta(get_the_ID(), 'zdravoe_article_content', true);
        $source = $custom ? $custom : get_the_content('');
        // Prepare content HTML and inject IDs into H2 if missing
        $content_html = apply_filters('the_content', $source);
        $used_ids = [];
        $content_html = preg_replace_callback('/<h2(?![^>]*id=)([^>]*)>(.*?)<\/h2>/is', function($m) use (&$used_ids){
          $text = trim(wp_strip_all_tags($m[2]));
          $base = sanitize_title($text ?: 'section');
          $id = $base; $k = 2;
          while (in_array($id, $used_ids, true)) { $id = $base.'-'.$k; $k++; }
          $used_ids[] = $id;
          return '<h2 id="'.esc_attr($id).'"'.$m[1].'>'.$m[2].'</h2>';
        }, $content_html);
        // Collect TOC entries (all H2 with ids)
        $toc = [];
        if (preg_match_all('/<h2[^>]*id="([^"]+)"[^>]*>(.*?)<\/h2>/is', $content_html, $mm, PREG_SET_ORDER)){
          foreach ($mm as $i => $m) {
            $toc[] = [ 'id' => wp_strip_all_tags($m[1]), 'title' => wp_strip_all_tags($m[2]) ];
          }
        }
        $rt = function_exists('zdravoe_reading_time') ? zdravoe_reading_time($source) : ['minutes'=>0,'words'=>0];
      ?>

      <a class="inline-flex items-center gap-2 text-[#8A6A5B] hover:text-[#7A5C4E] transition-colors mb-8" href="<?php echo esc_url( get_post_type_archive_link('article') ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5" aria-hidden="true"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
        Назад ко всем статьям
      </a>

      <div class="bg-white rounded-xl shadow-sm p-6 md:p-10 lg:p-12">
        <div class="grid lg:grid-cols-[350px_1fr] gap-12">
          <aside class="space-y-8 min-w-0">
            <?php if (has_post_thumbnail()): ?>
            <div class="rounded-xl overflow-hidden shadow-lg">
              <div class="aspect-[16/9] bg-gray-100">
                <?php // hero image removed by request ?>
              </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($toc)): ?>
            <div class="p-6 border border-gray-100 rounded-xl">
              <h3 class="serif text-[1.25rem] font-[700] text-gray-900 mb-4">Оглавление</h3>
              <nav class="space-y-3" data-article-toc>
                <?php foreach ($toc as $i => $t): ?>
                  <a href="#<?php echo esc_attr($t['id']); ?>" class="block w-full text-left px-4 py-2.5 rounded-lg hover:bg-[#FAF8F5] transition-colors text-gray-700 hover:text-[#8A6A5B]">
                    <?php echo esc_html($t['title']); ?>
                  </a>
                <?php endforeach; ?>
              </nav>
            </div>
            <?php endif; ?>

            <?php
              // Список статей текущего раздела (категории)
              $section_term = null;
              $terms = get_the_terms(get_the_ID(), 'article_category');
              if ($terms && !is_wp_error($terms)) {
                $section_term = array_shift($terms);
              }
              if ($section_term):
                $sec_q = new WP_Query([
                  'post_type'      => 'article',
                  'posts_per_page' => -1,
                  'no_found_rows'  => true,
                  'orderby'        => 'date',
                  'order'          => 'DESC',
                  'tax_query'      => [[
                    'taxonomy' => 'article_category',
                    'field'    => 'term_id',
                    'terms'    => [$section_term->term_id],
                  ]],
                ]);
            ?>
            <div class="p-6 border border-gray-100 rounded-xl">
              <h3 class="serif text-[1.25rem] font-[700] text-gray-900 mb-4">Статьи раздела: <?php echo esc_html($section_term->name); ?></h3>
              <nav class="space-y-2 max-h-[60vh] overflow-auto pr-1">
                <?php while ($sec_q->have_posts()): $sec_q->the_post();
                  $is_current = (get_the_ID() === get_queried_object_id());
                  $cls = 'block w-full text-left px-4 py-2.5 rounded-lg transition-colors ' . ($is_current ? 'bg-[#FAF8F5] text-[#8A6A5B]' : 'hover:bg-[#FAF8F5] text-gray-700 hover:text-[#8A6A5B]');
                ?>
                  <a href="<?php the_permalink(); ?>" class="<?php echo esc_attr($cls); ?>" <?php echo $is_current ? 'aria-current="page"' : ''; ?>>
                    <?php the_title(); ?>
                  </a>
                <?php endwhile; wp_reset_postdata(); ?>
              </nav>
            </div>
            <?php endif; ?>
          </aside>

          <main class="min-w-0">
            <header class="mb-6">
              <div class="text-sm text-gray-500 mb-3"><?php echo get_the_date('j F Y'); ?></div>
              <h1 class="serif break-words text-2xl sm:text-3xl lg:text-[2.25rem] font-[900] text-gray-900 leading-tight mb-2"><?php the_title(); ?></h1>
              <?php if ($adesc): ?>
                <p class="serif text-[1.25rem] text-gray-600 italic mb-6"><?php echo esc_html($adesc); ?></p>
              <?php endif; ?>
              <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Время чтения: ~<?php echo (int)$rt['minutes']; ?> мин
              </div>
              <div class="h-px bg-gradient-to-r from-[#8A6A5B]/30 via-[#8A6A5B]/30 to-transparent mb-6"></div>
            </header>

            <div class="prose max-w-none break-words" data-article-content>
              <?php echo $content_html; ?>
            </div>
          </main>
        </div>
      </div>
    <?php endwhile; endif; ?>
  </section>
</main>

<?php get_footer(); ?>

