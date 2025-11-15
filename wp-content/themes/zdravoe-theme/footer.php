<footer class="border-t border-gray-200 mt-16">
  <div class="container mx-auto px-6 py-10 grid md:grid-cols-3 gap-8 text-sm text-gray-700">
    <div class="space-y-2">
      <div class="serif text-lg font-bold text-gray-900">Здравое мышление</div>
      <p class="text-gray-600">Сайт о здравом христианском мышлении. Материалы: статьи и книги.</p>
    </div>
    <div>
      <div class="font-semibold text-gray-900 mb-3">Разделы</div>
      <ul class="space-y-2">
        <li><a class="hover:text-gray-900" href="<?php echo esc_url(home_url('/')); ?>">Главная</a></li>
        <li><a class="hover:text-gray-900" href="<?php echo esc_url(get_post_type_archive_link('book')); ?>">Книги</a></li>
        <li><a class="hover:text-gray-900" href="<?php echo esc_url(get_post_type_archive_link('article')); ?>">Тематические сборники статей</a></li>
        <?php if ($policy = get_page_by_path('privacy-policy')): ?>
          <li><a class="hover:text-gray-900" href="<?php echo esc_url(get_permalink($policy)); ?>">Политика конфиденциальности</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <div>
      <div class="font-semibold text-gray-900 mb-3">Контакты</div>
      <ul class="space-y-2">
        <li><a href="#" data-modal-open="contact-modal" class="hover:text-gray-900">Написать нам</a></li>
        <?php if ($about = get_page_by_path('about')): ?>
          <li><a class="hover:text-gray-900" href="<?php echo get_permalink($about); ?>">О сайте</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
  <div class="text-center text-xs text-gray-500 py-6">
    &copy; <?php echo date('Y'); ?> Здравое мышление. Все права защищены.
  </div>
</footer>

<!-- Contact Modal -->
<div class="fixed inset-0 z-50 hidden" data-modal="contact-modal" role="dialog" aria-modal="true" aria-labelledby="contact-title">
  <div class="absolute inset-0 bg-black/50" data-modal-close></div>
  <div class="relative mx-auto my-8 sm:my-20 w-[92%] max-w-lg max-h-[80vh] overflow-y-auto rounded-2xl bg-white p-6 shadow-lg">
    <div class="flex items-start justify-between mb-2">
      <h3 id="contact-title" class="serif text-xl font-bold text-gray-900">Связаться</h3>
      <button class="text-gray-500 hover:text-gray-700" type="button" aria-label="Close" data-modal-close>&times;</button>
    </div>
    <p class="text-sm text-gray-600 mb-4">Заполните форму — и мы ответим на ваш e‑mail.</p>
    <form class="space-y-4" data-contact-form>
      <input type="text" name="hp_field" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true" />
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="contact-name">Имя</label>
        <input id="contact-name" name="name" type="text" required class="w-full rounded-md border border-[#E7E0D8] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8A6A5B]" placeholder="Ваше имя" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="contact-email">Email</label>
        <input id="contact-email" name="email" type="email" required class="w-full rounded-md border border-[#E7E0D8] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8A6A5B]" placeholder="you@example.com" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1" for="contact-message">Сообщение</label>
        <textarea id="contact-message" name="message" rows="5" required class="w-full rounded-md border border-[#E7E0D8] px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8A6A5B]" placeholder="Текст сообщения"></textarea>
      </div>
      <div class="flex items-start gap-2">
        <input id="contact-consent" name="consent" type="checkbox" value="1" class="mt-1 h-4 w-4 rounded border-[#E7E0D8] text-[#8A6A5B] focus:ring-[#8A6A5B]" required />
        <label for="contact-consent" class="text-sm text-gray-700">
          Я соглашаюсь с обработкой персональных данных и
          <?php if ($policy = get_page_by_path('privacy-policy')): ?>
            <a href="<?php echo esc_url(get_permalink($policy)); ?>" class="underline text-[#8A6A5B] hover:text-[#7A5C4E]" target="_blank" rel="noopener">политикой конфиденциальности</a>
          <?php else: ?>
            политикой конфиденциальности
          <?php endif; ?>.
        </label>
      </div>
      <p class="text-sm" data-contact-status role="status" aria-live="polite"></p>
      <div class="flex justify-end gap-3 pt-2">
        <button type="button" data-modal-close class="px-4 py-2 rounded-md border border-[#E7E0D8] text-gray-700 hover:bg-gray-50">Отмена</button>
        <button type="submit" data-contact-submit class="px-4 py-2 rounded-full bg-[#8A6A5B] text-white hover:bg-[#7A5C4E]">Отправить</button>
      </div>
    </form>
  </div>
</div>

<!-- Cookie Consent -->
<div class="fixed bottom-0 inset-x-0 z-50 hidden" data-cookie-consent>
  <div class="container mx-auto max-w-7xl px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-6 bg-[#FAF8F5] text-[#8A6A5B] border border-[#E7E0D8] shadow-md rounded-t-2xl">
    <p class="text-sm leading-snug">
      Продолжая использовать сайт, вы соглашаетесь с использованием файлов cookie.
      <?php if ($policy = get_page_by_path('privacy-policy')): ?>
        <a href="<?php echo esc_url(get_permalink($policy)); ?>" class="underline text-[#8A6A5B] hover:text-[#7A5C4E]">Подробнее</a>
      <?php endif; ?>
    </p>
    <div class="flex-1"></div>
    <button type="button" data-cookie-accept class="px-4 py-2 rounded-full bg-[#8A6A5B] text-white hover:bg-[#7A5C4E] text-sm">Ок</button>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
