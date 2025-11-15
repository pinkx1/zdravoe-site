<?php get_header(); ?>

<main class="min-h-screen">

	<!-- Hero Section -->
	<section class="container mx-auto px-6 py-16 max-w-7xl">
		<div class="grid md:grid-cols-2 gap-12 items-center">
			<div class="space-y-6">
				<h1 class="serif text-[3rem] font-[900] text-gray-900 leading-tight">
					Здравое христианство без лишнего шума
				</h1>
				<p class="text-[1.125rem] text-gray-600 leading-relaxed">
					Если вы попали сюда не с главной страницы, это всё равно то же самое место: спокойные тексты о вере, Библии и христианской жизни.
				</p>
				<p class="text-[1.125rem] text-gray-600 leading-relaxed">
					Никаких кричащих заголовков — только книги и статьи, которые можно перечитывать и обсуждать, возвращаясь к Писанию.
				</p>

			</div>

			<div class="relative">
				<!-- hero image intentionally убран -->
			</div>
		</div>
	</section>

	<!-- About Section -->
	<section class="bg-[#C9B8A8] text-gray-900 py-20 mt-16 font-sans">
		<div class="container mx-auto px-6 max-w-7xl">
			<div class="grid md:grid-cols-2 gap-12 items-center">
				<div>
					<!-- about-section image intentionally убран -->
				</div>
				<div class="space-y-6">
					<blockquote class="serif text-xl sm:text-2xl md:text-[1.75rem] font-[700] leading-tight tracking-[-0.025em] text-gray-900">
						«Будьте мудры, испытывайте всё, хорошего держитесь.»
					</blockquote>
					<p class="text-gray-700 italic text-base">по мотивам 1 Фессалоникийцам 5:21</p>
					<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
						Мы поощряем не поспешные выводы, а вдумчивое чтение, честные вопросы и открытый разговор о сложных темах веры.
					</p>
					<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
						Пусть эти материалы станут поводом не для споров, а для того, чтобы вместе внимательнее посмотреть на текст Писания.
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Books Section -->
	<section class="container mx-auto px-6 py-20 max-w-7xl">
		<div class="flex flex-col items-start min-[400px]:flex-row min-[400px]:items-center min-[400px]:justify-between gap-4 mb-10">
			<h2 class="serif text-[2.5rem] font-[900] text-gray-900">Книги</h2>
			<a href="<?php echo get_post_type_archive_link('book'); ?>" class="bg-[#8A6A5B] text-white px-6 py-3 rounded-full hover:bg-[#7A5C4E] transition-colors inline-flex items-center gap-2">
				Все книги
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>

		<div class="overflow-x-auto -mx-6 px-6">
			<div class="flex gap-6 pb-4" style="width: max-content;">
				<?php
        $books = new WP_Query([
          'post_type'      => 'book',
          'posts_per_page' => 10,
        ]);
        while ($books->have_posts()) : $books->the_post(); ?>
				<div class="w-[280px] flex-shrink-0">
					<a href="<?php the_permalink(); ?>" class="group block h-full rounded-xl border border-gray-200 bg-white/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
						<div class="flex h-full flex-col p-5">
							<h3 class="serif text-[1.25rem] md:text-[1.375rem] font-[800] text-gray-900 leading-snug line-clamp-3">
								<?php the_title(); ?>
							</h3>
						</div>
					</a>
				</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<!-- Articles Section -->
	<section class="container mx-auto px-6 py-20 max-w-7xl">
		<div class="flex flex-col items-start min-[400px]:flex-row min-[400px]:items-center min-[400px]:justify-between gap-4 mb-10">
			<h2 class="serif text-[2.5rem] font-[900] text-gray-900">Тематические сборники статей</h2>
			<a href="<?php echo get_post_type_archive_link('article'); ?>" class="bg-[#8A6A5B] text-white px-6 py-3 rounded-full hover:bg-[#7A5C4E] transition-colors inline-flex items-center gap-2">
				Все статьи
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>

		<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
			<?php
      $articles = new WP_Query([
        'post_type'      => 'article',
        'posts_per_page' => 6,
      ]);
      while ($articles->have_posts()) : $articles->the_post(); ?>
			<a href="<?php the_permalink(); ?>" class="group block h-full rounded-xl border border-gray-200 bg-white/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
					<div class="flex h-full flex-col p-5 gap-3">
						<?php
						  $label_terms = get_the_terms(get_the_ID(), 'article_category');
						  $label       = (!is_wp_error($label_terms) && !empty($label_terms)) ? $label_terms[0]->name : '';
						?>
						<?php if (!empty($label)) : ?>
							<div class="inline-flex items-center rounded-full bg-[#F4ECE6] px-3 py-1 text-[0.75rem] font-medium text-[#8A6A5B]">
								<?php echo esc_html($label); ?>
							</div>
						<?php endif; ?>
						<h3 class="serif text-[1.25rem] md:text-[1.375rem] font-[800] text-gray-900 leading-snug line-clamp-3">
							<?php the_title(); ?>
						</h3>
					</div>
			</a>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>
