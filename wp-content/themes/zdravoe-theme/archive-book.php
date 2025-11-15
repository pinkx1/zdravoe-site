<?php get_header(); ?>

<main class="min-h-screen">
  <section class="container mx-auto px-6 py-16 max-w-7xl">
    <h1 class="serif text-[3rem] font-[900] text-gray-900 mb-4">Книги</h1>
    <p class="text-[1.125rem] text-gray-600 mb-12 max-w-3xl">
      Подборка книг для внимательного чтения Библии и ростa в христианской жизни.
    </p>

    <?php if (have_posts()) : ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        <?php while (have_posts()) : the_post(); ?>
          <div class="w-full h-full">
            <a href="<?php the_permalink(); ?>" class="group block h-full rounded-xl border border-gray-200 bg-white/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
              <div class="flex h-full flex-col p-5">
                <h3 class="serif text-[1.25rem] md:text-[1.375rem] font-[800] text-gray-900 leading-snug line-clamp-3">
                  <?php the_title(); ?>
                </h3>
              </div>
            </a>
          </div>
        <?php endwhile; ?>
      </div>

      <?php
        $links = paginate_links([
          'type'      => 'array',
          'prev_next' => false,
          'mid_size'  => 1,
        ]);
        if ($links) :
      ?>
        <div class="flex justify-center gap-2 mt-12">
          <?php foreach ($links as $l) :
            $text        = wp_strip_all_tags($l);
            $is_current  = strpos($l, 'current') !== false;
            $is_link     = strpos($l, 'href=') !== false;
            $base_classes    = 'w-10 h-10 rounded-full inline-flex items-center justify-center transition-colors';
            $active_classes  = ' bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]';
            $inactive_classes = ' bg-white border border-gray-300 text-gray-700 hover:bg-gray-50';

            if ($is_current) {
              echo '<span class="' . $base_classes . $active_classes . '">' . esc_html($text) . '</span>';
            } elseif ($is_link) {
              if (preg_match('/href=\"([^\"]+)\"/i', $l, $m)) {
                echo '<a class="' . $base_classes . $inactive_classes . '" href="' . esc_url($m[1]) . '">' . esc_html($text) . '</a>';
              }
            }
          endforeach; ?>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <p class="text-gray-600">Пока здесь нет опубликованных книг.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>
