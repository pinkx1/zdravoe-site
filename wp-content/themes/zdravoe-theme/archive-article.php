<?php get_header(); ?>

<main class="min-h-screen">
  <section class="container mx-auto px-6 py-16 max-w-7xl">
    <h1 class="serif text-[3rem] font-[900] text-gray-900 mb-4">Тематические сборники статей</h1>
    <p class="text-[1.125rem] text-gray-600 mb-12 max-w-3xl">
      Здесь собраны тематические сборники статей. Каждый раздел — это отдельный сборник, внутри которого расположены статьи по одной теме.
    </p>

    <?php
      $terms = get_terms([
        'taxonomy'   => 'article_category',
        'hide_empty' => true,
      ]);
    ?>

    <?php if (!is_wp_error($terms) && !empty($terms)) : ?>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        <?php foreach ($terms as $term) : ?>
          <a
            href="<?php echo esc_url(get_term_link($term)); ?>"
            class="group block h-full rounded-xl border border-gray-200 bg-white/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
          >
            <div class="flex h-full flex-col p-5">
              <h3 class="serif text-[1.25rem] md:text-[1.375rem] font-[800] text-gray-900 leading-snug mb-2 line-clamp-3">
                <?php echo esc_html($term->name); ?>
              </h3>
              <?php if (!empty($term->description)) : ?>
                <p class="text-gray-600 text-sm leading-relaxed line-clamp-3 mb-3">
                  <?php echo esc_html($term->description); ?>
                </p>
              <?php endif; ?>
              <p class="mt-auto text-xs uppercase tracking-wide text-gray-500">
                <?php echo intval($term->count); ?> статей в сборнике
              </p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p class="text-gray-600">Пока здесь нет тематических сборников статей.</p>
    <?php endif; ?>
  </section>
</main>

<?php get_footer(); ?>

