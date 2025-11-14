<?php get_header(); ?>

<main class="min-h-screen">

	<!-- Hero Section -->
	<section class="container mx-auto px-6 py-16 max-w-7xl">
		<div class="grid md:grid-cols-2 gap-12 items-center">
			<div class="space-y-6">
				<h1 class="serif text-[3rem] font-[900] text-gray-900 leading-tight">
					Здравое мышление в мире опасностей
				</h1>
				<p class="text-[1.125rem] text-gray-600 leading-relaxed">
					По духу в материалах сайта звучит призыв к здравомыслию читателя, чтобы он смог научиться отделять зерна истины Учения Христа от плевел научно-религиозных идей, догматов, толкований.
				</p>
				<p class="text-[1.125rem] text-gray-600 leading-relaxed">
					Автором статей и книг является Анатолий Ададуров.
				</p>

			</div>

			<div class="relative">
				<img src="https://images.unsplash.com/photo-1641491616155-2864f4d3afe1?w=800" alt="Christian cross silhouette" class="w-full h-[500px] object-cover rounded-2xl shadow-lg" />
			</div>
		</div>
	</section>

	<!-- About Section -->
	<section class="bg-[#C9B8A8] text-gray-900 py-20 mt-16 font-sans">
		<div class="container mx-auto px-6 max-w-7xl">
			<div class="grid md:grid-cols-2 gap-12 items-center">
				<div>
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/bible.png' ); ?>" alt="Bible pages" class="w-full h-64 md:h-[400px] object-contain rounded-xl" />
				</div>
				<div class="space-y-6">
					<blockquote class="serif text-xl sm:text-2xl md:text-[1.75rem] font-[700] leading-tight tracking-[-0.025em] text-gray-900">
						"И вот благовестие, которое мы слышали от Него и возвещаем вам: Бог есть свет, и нет в Нем никакой тьмы"
					</blockquote>
					<p class="text-gray-700 italic text-base">— 1 Иоанна 1:5</p>
					<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
						Никто не приходит в мир с готовыми взглядами на жизнь. Люди рождаются свободными, как от всяких наук, так и от разных религий. Каждый имеет право идти своей дорогой по жизни.
					</p>
					<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
						При этом есть одно, что неизбежно, раньше или позже объединяет всех. Называется оно – гроб, смерть. Почему человек умирает? Откуда берется несчастье? Из-за чего большинство людей даже не могут просто дожить до старости? Это – по-настоящему трудные вопросы.
					</p>
					<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
						Настоящий сайт создан для людей, как склонных к вере в Бога, так и для тех, кто не задумывается о Боге. В способности здраво рассуждать нуждаются все. Тем более – в наше время.
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
				Смотреть все
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>

		<div class="overflow-x-auto -mx-6 px-6">
			<div class="flex gap-6 pb-4" style="width: max-content;">
				<?php
        $books = new WP_Query([
          'post_type' => 'book',
          'posts_per_page' => 10,
        ]);
        while ($books->have_posts()) : $books->the_post(); ?>
				<div class="w-[280px] flex-shrink-0">
					<a href="<?php the_permalink(); ?>" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg hover:scale-[1.02] transition-all duration-300">
						<div class="aspect-[3/4] overflow-hidden bg-gray-100">
							<?php if (has_post_thumbnail()) {
								the_post_thumbnail('book_card', ['class' => 'w-full h-full object-cover']);
							} else {
								$ph_file = get_template_directory() . '/assets/images/book_placeholder.webp';
								$ph = get_template_directory_uri() . '/assets/images/book_placeholder.webp' . (file_exists($ph_file) ? ('?ver=' . filemtime($ph_file)) : '');
									echo '<img src="' . esc_url($ph) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover" loading="lazy" decoding="async" />';
							} ?>
						</div>
						<div class="p-5">
							<h3 class="serif text-[1.125rem] font-[700] text-gray-900 mb-2 line-clamp-2"><?php the_title(); ?></h3>
							<p class="text-gray-600 line-clamp-2"><?php the_excerpt(); ?></p>
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
			<h2 class="serif text-[2.5rem] font-[900] text-gray-900">Статьи</h2>
			<a href="<?php echo get_post_type_archive_link('article'); ?>" class="bg-[#8A6A5B] text-white px-6 py-3 rounded-full hover:bg-[#7A5C4E] transition-colors inline-flex items-center gap-2">
				Все статьи
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>

		<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
			<?php
      $articles = new WP_Query([
        'post_type' => 'article',
        'posts_per_page' => 6,
      ]);
      while ($articles->have_posts()) : $articles->the_post(); ?>
			<a href="<?php the_permalink(); ?>" class="block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
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
						  the_time('j F Y'); 
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
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>
