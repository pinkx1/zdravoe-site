<?php get_header(); ?>

<main class="min-h-screen">
  <section class="container mx-auto px-6 py-16 max-w-7xl">
    <h1 class="serif text-[3rem] font-[900] text-gray-900 mb-4">Книги</h1>
    <p class="text-[1.125rem] text-gray-600 mb-12 max-w-3xl">
      Коллекция книг для углубления вашей веры и понимания христианского учения.
    </p>

    <?php if (have_posts()): ?>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php while (have_posts()): the_post(); ?>
          <div class="w-full">
            <a href="<?php the_permalink(); ?>" class="group block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
              <div class="aspect-[3/4] overflow-hidden bg-gray-100">
                <?php if (has_post_thumbnail()) {
                  the_post_thumbnail('book_card', ['class' => 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105']);
                } else {
                  $ph_file = get_template_directory() . '/assets/images/book_placeholder.webp';
                  $ph = get_template_directory_uri() . '/assets/images/book_placeholder.webp' . (file_exists($ph_file) ? ('?ver=' . filemtime($ph_file)) : '');
                  echo '<img src="' . esc_url($ph) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy" decoding="async" />';
                } ?>
              </div>
              <div class="p-5">
                <h3 class="serif text-[1.125rem] font-[700] text-gray-900 mb-2 line-clamp-2"><?php the_title(); ?></h3>
                <p class="text-gray-600 line-clamp-2"><?php echo get_the_excerpt(); ?></p>
              </div>
            </a>
          </div>
        <?php endwhile; ?>
      </div>

      <?php
        $links = paginate_links([
          'type' => 'array',
          'prev_next' => false,
          'mid_size' => 1,
        ]);
        if ($links) :
      ?>
        <div class="flex justify-center gap-2 mt-12">
          <?php foreach ($links as $l):
            $text = wp_strip_all_tags($l);
            $is_current = strpos($l, 'current') !== false;
            $is_link = strpos($l, 'href=') !== false;
            $base_classes = 'w-10 h-10 rounded-full inline-flex items-center justify-center transition-colors';
            $active_classes = ' bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]';
            $inactive_classes = ' bg-white border border-gray-300 text-gray-700 hover:bg-gray-50';
            if ($is_current) {
              echo '<span class="' . $base_classes . $active_classes . '">' . esc_html($text) . '</span>';
            } elseif ($is_link) {
              // Extract href
              if (preg_match('/href=\"([^\"]+)\"/i', $l, $m)) {
                echo '<a class="' . $base_classes . $inactive_classes . '" href="' . esc_url($m[1]) . '">' . esc_html($text) . '</a>';
              }
            }
          endforeach; ?>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <p class="text-gray-600">Пока нет книг.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
