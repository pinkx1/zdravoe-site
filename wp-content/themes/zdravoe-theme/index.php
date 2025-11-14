<?php get_header(); ?>

<main class="container mx-auto px-6 py-12 max-w-4xl">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article class="mb-12">
      <h2 class="serif text-2xl font-bold mb-2"><a class="hover:underline" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div class="text-gray-500 text-sm mb-3"><?php echo get_the_date('j F Y'); ?></div>
      <div class="prose">
        <?php the_excerpt(); ?>
      </div>
    </article>
  <?php endwhile; else: ?>
    <p>Записей не найдено.</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>
