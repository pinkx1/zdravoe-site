<?php
get_header();
?>

<main class="min-h-screen">

	<!-- Hero Section -->
	<section class="container mx-auto px-6 py-16 max-w-7xl">
		<div class="grid md:grid-cols-2 gap-12 items-center">
			<div class="space-y-6">
				<h1 class="serif text-[#8A6A5B] leading-tight">
					<span class="block text-[3rem] md:text-[3.25rem] font-[900]">
						ЗДРАВОЕ МЫШЛЕНИЕ
					</span>
					<span class="block mt-1 text-[2.5rem] md:text-[2.75rem] font-[700] italic">
						В МИРЕ ОПАСНОСТЕЙ
					</span>
				</h1>
				<p class="text-[1.125rem] text-gray-600 leading-relaxed">
					Автором статей и книг является Анатолий Ададуров.
				</p>
				<p class="text-[1.125rem] text-gray-600 leading-relaxed">
					По духу в материалах сайта звучит призыв к здравомыслию читателя, чтобы он смог научиться отделять зерна истины Учения Христа от плевел научно-религиозных идей, догматов, толкований. Все материалы сайта составлены в соответствии со Священным Писанием и Учением Христа, в чем читатель имеет возможность убедиться сам.
				</p>
			</div>

			<div class="relative">
				<div class="aspect-[4/3] rounded-2xl overflow-hidden">
					<img
						src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/front-2_1.webp' ); ?>"
						alt="Человек, читающий книгу и размышляющий"
						class="w-full h-full object-cover"
						loading="lazy"
						decoding="async"
					/>
				</div>
			</div>
		</div>
	</section>

	<!-- About Section -->
	<section class="bg-[#C9B8A8] text-gray-900 py-20 mt-16 font-sans">
		<div class="container mx-auto px-6 max-w-7xl">
			<div class="space-y-6">
				<p class="uppercase tracking-[0.25em] text-xs font-semibold text-[#8A6A5B]">
					О ПРОЕКТЕ
				</p>
				<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
					Никто не приходит в мир с готовыми взглядами на жизнь. Люди рождаются свободными, как от всяких наук, так и от разных религий. Каждый имеет право идти своей дорогой по жизни.
				</p>
				<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
					При этом есть одно, что неизбежно, раньше или позже объединяет всех. Называется оно – гроб, смерть.
				</p>
				<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
					Почему человек умирает? Откуда берется несчастье? Из-за чего большинство людей даже не могут просто дожить до старости? Это – по-настоящему трудные вопросы. Какой нужен ум, чтобы их разрешить? И где взять его?
				</p>
				<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
					Все хорошо знают, что ум человеку дается от Бога. Вопрос: Кому Бог дает ум? Даст ли Бог ума человеку злому? Едва ли. Бог и зло – понятия несовместимые. Почему же тогда злые и нечестные люди успешны в своих делах и хорошо живут? Разве их жизнь не свидетельствует об их уме? Или ум есть что-то другое?
				</p>
				<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
					Настоящий сайт создан для людей, как склонных к вере в Бога, так и для тех, кто не задумывается о Боге. В способности здраво рассуждать нуждаются все. Тем более – в наше время.
				</p>
				<p class="text-gray-800 leading-relaxed text-base sm:text-lg">
					Концепция сайта укладывается в один стих из послания Апостола Иоанна:
				</p>
				<blockquote class="serif text-lg sm:text-xl md:text-[1.5rem] font-[600] leading-snug tracking-[-0.015em] text-gray-900">
					«И вот благовестие, которое мы слышали от Него и возвещаем вам: Бог есть свет, и нет в Нем никакой тьмы».
				</blockquote>
				<p class="text-gray-700 italic text-sm">
					(1&nbsp;Иоанна&nbsp;1:5)
				</p>
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

		<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
			<?php
      $books = new WP_Query([
        'post_type'      => 'book',
        'posts_per_page' => 6,
        'orderby'        => 'rand',
      ]);
      if ($books->have_posts()) :
        while ($books->have_posts()) : $books->the_post(); ?>
			<a href="<?php the_permalink(); ?>" class="group block h-full rounded-xl border border-gray-200 bg-white/90 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
				<div class="flex h-full flex-col p-5">
					<h3 class="serif text-[1.25rem] md:text-[1.375rem] font-[800] text-gray-900 leading-snug line-clamp-3">
						<?php the_title(); ?>
					</h3>
				</div>
			</a>
			<?php endwhile;
        wp_reset_postdata();
      else : ?>
			<p class="text-gray-600">Книги пока не добавлены.</p>
			<?php endif; ?>
		</div>
	</section>

	<!-- Article Collections Section -->
	<section class="container mx-auto px-6 py-20 max-w-7xl">
		<div class="flex flex-col items-start min-[400px]:flex-row min-[400px]:items-center min-[400px]:justify-between gap-4 mb-10">
			<h2 class="serif text-[2.5rem] font-[900] text-gray-900">Тематические сборники статей</h2>
			<a href="<?php echo get_post_type_archive_link('article'); ?>" class="bg-[#8A6A5B] text-white px-6 py-3 rounded-full hover:bg-[#7A5C4E] transition-colors inline-flex items-center gap-2">
				Все сборники
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>

		<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
			<?php
      $collections = get_terms([
        'taxonomy'   => 'article_category',
        'hide_empty' => true,
      ]);

      if (!is_wp_error($collections) && !empty($collections)) :
        shuffle($collections);
        $collections = array_slice($collections, 0, 6);

        foreach ($collections as $term) : ?>
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
						<?php echo intval($term->count); ?> материалов
					</p>
				</div>
			</a>
			<?php endforeach;
      else : ?>
			<p class="text-gray-600">Сборники статей пока не добавлены.</p>
			<?php endif; ?>
		</div>
	</section>

</main>

<?php get_footer(); ?>
