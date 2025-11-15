<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script>
      // Tailwind CDN config (optional customizations)
      window.tailwind = window.tailwind || {};
      tailwind.config = {
        theme: {
          extend: {}
        }
      }
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=typography,aspect-ratio,line-clamp"></script>
    <!-- Turbo Drive for smoother navigation -->
    <script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js" defer></script>
    <?php wp_head(); ?>
    </head>
<body <?php body_class(); ?>>
<header class="sticky top-0 z-50 w-full bg-white border-b border-gray-100 shadow-sm">
  <div class="container mx-auto px-6 py-5 flex items-center justify-between max-w-7xl">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="serif text-[1.5rem] font-[700] text-[#8A6A5B] tracking-tight hover:opacity-80 transition-opacity">
      Здравое мышление
    </a>

    <!-- Mobile hamburger -->
    <button type="button" class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100" aria-label="Открыть меню" aria-expanded="false" aria-controls="mobile-menu" data-mobile-menu-toggle>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <!-- Desktop nav + CTA -->
    <div class="hidden md:flex items-center gap-8">
      <nav class="flex items-center gap-8">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="<?php echo (is_front_page() || is_home()) ? 'px-4 py-2 rounded-full transition-all bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]' : 'px-4 py-2 rounded-full transition-all text-gray-700 hover:bg-gray-100'; ?>">Главная</a>
        <a href="<?php echo esc_url(get_post_type_archive_link('book')); ?>" class="<?php echo (is_post_type_archive('book') || is_singular('book')) ? 'px-4 py-2 rounded-full transition-all bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]' : 'px-4 py-2 rounded-full transition-all text-gray-700 hover:bg-gray-100'; ?>">Книги</a>
        <a href="<?php echo esc_url(get_post_type_archive_link('article')); ?>" class="<?php echo (is_post_type_archive('article') || is_singular('article')) ? 'px-4 py-2 rounded-full transition-all bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]' : 'px-4 py-2 rounded-full transition-all text-gray-700 hover:bg-gray-100'; ?>">Тематические сборники статей</a>
      </nav>
      <button type="button" data-modal-open="contact-modal" class="bg-[#8A6A5B] text-white px-6 py-2.5 rounded-full hover:bg-[#7A5C4E] transition-colors">Связь с автором</button>
    </div>
  </div>

  <!-- Mobile dropdown menu -->
  <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-100 shadow-sm" data-mobile-menu>
    <div class="container mx-auto max-w-7xl px-6 pb-4 space-y-2">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="block w-full text-left <?php echo (is_front_page() || is_home()) ? 'px-4 py-2 rounded-full transition-all bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]' : 'px-4 py-2 rounded-full transition-all text-gray-700 hover:bg-gray-100'; ?>">Главная</a>
      <a href="<?php echo esc_url(get_post_type_archive_link('book')); ?>" class="block w-full text-left <?php echo (is_post_type_archive('book') || is_singular('book')) ? 'px-4 py-2 rounded-full transition-all bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]' : 'px-4 py-2 rounded-full transition-all text-gray-700 hover:bg-gray-100'; ?>">Книги</a>
      <a href="<?php echo esc_url(get_post_type_archive_link('article')); ?>" class="block w-full text-left <?php echo (is_post_type_archive('article') || is_singular('article')) ? 'px-4 py-2 rounded-full transition-all bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]' : 'px-4 py-2 rounded-full transition-all text-gray-700 hover:bg-gray-100'; ?>">Тематические сборники статей</a>
      <button type="button" data-modal-open="contact-modal" class="w-full px-4 py-2 rounded-full bg-[#8A6A5B] text-white hover:bg-[#7A5C4E] transition-colors">Связь с автором</button>
    </div>
  </div>

</header>
