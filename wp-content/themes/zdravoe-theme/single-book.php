<?php get_header(); ?>

<main class="min-h-screen">
  <section class="container mx-auto px-6 py-16 max-w-7xl">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <?php
        $excerpt = get_the_excerpt();
        $chapters_meta = get_post_meta(get_the_ID(), 'zdravoe_book_chapters', true);
        if (!is_array($chapters_meta)) { $chapters_meta = []; }

        // Собираем массив глав для вывода по одной
        $chapters_data = [];
        if ($chapters_meta && count($chapters_meta) > 0) {
          $ids = [];
          foreach ($chapters_meta as $idx => $ch) {
            $t = isset($ch['title']) ? wp_strip_all_tags($ch['title']) : '';
            $base = sanitize_title($t ?: ('chapter-'.($idx+1)));
            $id = $base; $k = 2;
            while (in_array($id, $ids, true)) { $id = $base.'-'.$k; $k++; }
            $ids[] = $id;
            $chapters_data[] = [
              'id' => $id,
              'title' => $t,
              'content_html' => wpautop(wp_kses_post(isset($ch['content']) ? $ch['content'] : '')),
            ];
          }
        } else {
          // Fallback: разбиваем контент по H2 на главы
          $raw = get_the_content('');
          $content_html = apply_filters('the_content', $raw);
          $parts = preg_split('/<h2[^>]*>(.*?)<\/h2>/is', $content_html, -1, PREG_SPLIT_DELIM_CAPTURE);
          // parts: [before, h2_text1, between1, h2_text2, between2, ...]
          if (count($parts) > 1) {
            for ($i = 1; $i < count($parts); $i += 2) {
              $title = wp_strip_all_tags($parts[$i]);
              $body = ($i+1 < count($parts)) ? $parts[$i+1] : '';
              $id = sanitize_title($title ?: ('section-'.(($i+1)/2)));
              $chapters_data[] = [
                'id' => $id,
                'title' => $title,
                'content_html' => $body,
              ];
            }
          } else {
            // Нет H2 — считаем единственной главой весь контент
            $chapters_data[] = [
              'id' => 'content',
              'title' => 'Содержание',
              'content_html' => $content_html,
            ];
          }
        }

        $total = count($chapters_data);
        $current = isset($_GET['ch']) ? max(1, (int) $_GET['ch']) : 1;
        if ($current > $total) { $current = $total; }
        $current_item = $chapters_data[$current-1];

        $permalink = get_permalink();
        $prev_url = ($current > 1) ? add_query_arg('ch', $current-1, $permalink) : '';
        $next_url = ($current < $total) ? add_query_arg('ch', $current+1, $permalink) : '';

        // Reading time estimates
        $book_words = 0;
        foreach ($chapters_data as $cd) {
          if (function_exists('zdravoe_reading_time')) {
            $r = zdravoe_reading_time($cd['content_html']);
            $book_words += (int)$r['words'];
          }
        }
        $book_minutes = $book_words ? max(1, (int)ceil($book_words / 220)) : 0;
        $chapter_rt = function_exists('zdravoe_reading_time') ? zdravoe_reading_time($current_item['content_html']) : ['minutes'=>0,'words'=>0];
      ?>

      <a class="inline-flex items-center gap-2 text-[#8A6A5B] hover:text-[#7A5C4E] transition-colors mb-8" href="<?php echo esc_url( get_post_type_archive_link('book') ); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5" aria-hidden="true"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
        Назад к списку книг
      </a>

      <div class="grid lg:grid-cols-[350px_1fr] gap-12">
        <aside class="space-y-8 min-w-0">
          <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="aspect-[3/4] bg-gray-100">
              <?php if (has_post_thumbnail()) {
                the_post_thumbnail('large', ['class' => 'w-full h-full object-cover']);
              } else {
                $ph_file = get_template_directory() . '/assets/images/book_placeholder.webp';
                $ph = get_template_directory_uri() . '/assets/images/book_placeholder.webp' . (file_exists($ph_file) ? ('?ver=' . filemtime($ph_file)) : '');
                echo '<img src="' . esc_url($ph) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover" loading="lazy" decoding="async" />';
              } ?>
            </div>
          </div>
          <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="serif text-[1.25rem] font-[700] text-gray-900 mb-4">Оглавление</h3>
            <nav class="space-y-3" data-book-toc>
              <?php if (!empty($chapters_data)):
                foreach ($chapters_data as $idx => $t):
                  $url = add_query_arg('ch', $idx+1, $permalink);
                  $is_current = ($idx+1) === $current;
                  $cls = 'block w-full text-left px-4 py-2.5 rounded-lg transition-colors ' . ($is_current ? 'bg-[#FAF8F5] text-[#8A6A5B]' : 'hover:bg-[#FAF8F5] text-gray-700 hover:text-[#8A6A5B]');
                ?>
                  <a href="<?php echo esc_url($url); ?>" data-ch="<?php echo (int)($idx+1); ?>" class="<?php echo esc_attr($cls); ?>" <?php echo $is_current ? 'aria-current="page"' : ''; ?>>
                    <?php echo esc_html($t['title']); ?>
                  </a>
                <?php endforeach;
              else: ?>
                <p class="text-gray-500">Содержание появится после добавления глав или заголовков H2.</p>
              <?php endif; ?>
            </nav>
          </div>
        </aside>

        <main class="bg-white rounded-xl shadow-sm p-6 md:p-10 lg:p-12 min-w-0">
          <h1 class="serif break-words text-2xl sm:text-3xl md:text-4xl lg:text-[3rem] font-[900] text-gray-900 mb-4 leading-tight"><?php the_title(); ?></h1>
          <?php if ($excerpt): ?>
            <p class="serif text-[1.5rem] text-gray-600 italic mb-8"><?php echo esc_html($excerpt); ?></p>
          <?php endif; ?>
          <?php if ($book_minutes): ?>
          <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Время чтения всей книги: ~<?php echo (int)$book_minutes; ?> мин
          </div>
          <?php endif; ?>
          <div class="h-px bg-gradient-to-r from-[#8A6A5B] via-[#8A6A5B]/30 to-transparent mb-8"></div>

          <div class="prose prose-lg max-w-none break-words">
            <h2 id="<?php echo esc_attr($current_item['id']); ?>" data-book-chapter-title class="serif break-words text-xl sm:text-2xl md:text-[1.75rem] font-[700] text-gray-900 mt-12 mb-2"><?php echo esc_html($current_item['title']); ?></h2>
            <?php if (!empty($chapter_rt['minutes'])): ?>
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              Время чтения главы: ~<?php echo (int)$chapter_rt['minutes']; ?> мин
            </div>
            <?php endif; ?>
            <div class="text-gray-700 leading-[1.6] mb-6" data-book-chapter-content><?php echo $current_item['content_html']; ?></div>
          </div>

          <div class="flex gap-4 mt-12 pt-8 border-t border-gray-200">
            <a href="<?php echo $prev_url ? esc_url($prev_url) : '#'; ?>" data-prev-chapter class="flex-1 bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-full hover:bg-gray-50 transition-colors inline-flex items-center justify-center gap-2 <?php echo $current <= 1 ? 'hidden' : ''; ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5" aria-hidden="true"><path d="m15 18-6-6 6-6"></path></svg>
              Предыдущая глава
            </a>
            <a href="<?php echo $next_url ? esc_url($next_url) : '#'; ?>" data-next-chapter class="flex-1 bg-[#8A6A5B] text-white px-6 py-3 rounded-full hover:bg-[#7A5C4E] transition-colors inline-flex items-center justify-center gap-2 <?php echo $current >= $total ? 'hidden' : ''; ?>">
              Следующая глава
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5" aria-hidden="true"><path d="m9 18 6-6-6-6"></path></svg>
            </a>
          </div>
          <script id="book-chapters-json" type="application/json"><?php echo wp_json_encode($chapters_data, JSON_UNESCAPED_UNICODE); ?></script>
        </main>
      </div>
    <?php endwhile; endif; ?>
  </section>
</main>

<?php get_footer(); ?>
