<?php get_header(); ?>

<main class="container mx-auto px-6 py-12 max-w-4xl">
  <?php if (have_posts()): while (have_posts()): the_post(); ?>
    <article>
      <h1 class="serif text-[2rem] font-[900] text-gray-900 mb-6"><?php the_title(); ?></h1>
      <div class="prose max-w-none">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
