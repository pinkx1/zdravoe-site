<?php
// Подключаем стили темы (если нужно)
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'zdravoe-main-style',                  // Уникальный хендл
        get_stylesheet_uri(),                  // Путь к style.css в корне темы
        [],                                    // Зависимости
        filemtime(get_template_directory() . '/style.css'), // Автоверсия — чтобы не кэшировалось
        'all'                                  // Для всех типов устройств
    );

    // Скрипт темы (для модалок и пр.)
    $theme_js = get_template_directory() . '/assets/theme.js';
    if (file_exists($theme_js)) {
        wp_enqueue_script(
            'zdravoe-theme-js',
            get_template_directory_uri() . '/assets/theme.js',
            [],
            filemtime($theme_js),
            true
        );

        // Данные для JS (AJAX + nonce)
        wp_localize_script('zdravoe-theme-js', 'ZdravoeTheme', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('zdravoe_contact_nonce'),
            'articles_nonce' => wp_create_nonce('zdravoe_articles_nonce'),
        ]);
    }
});

// Базовые настройки темы
add_action('after_setup_theme', function () {
    // Автоматический <title> через wp_head()
    add_theme_support('title-tag');

    // Миниатюры для записей и CPT
    add_theme_support('post-thumbnails');

    // Доп. размеры изображений для карточек/обложек
    // Книги: карточка 3:4
    add_image_size('book_card', 600, 800, true);
    // Статьи: карточка ~16:10 и hero 16:9
    add_image_size('article_card', 800, 500, true);
    add_image_size('article_hero', 1200, 675, true);
});

// Показываем кастомные размеры в диалоге выбора размеров (медиа)
add_filter('image_size_names_choose', function ($sizes) {
    $sizes['book_card'] = 'Обложка книги (3:4)';
    $sizes['article_card'] = 'Обложка статьи — карточка (16:10)';
    $sizes['article_hero'] = 'Обложка статьи — герой (16:9)';
    return $sizes;
});

// Обработчик формы "Связь с автором"
add_action('wp_ajax_nopriv_zdravoe_contact', 'zdravoe_handle_contact');
add_action('wp_ajax_zdravoe_contact', 'zdravoe_handle_contact');

function zdravoe_handle_contact() {
    // Проверяем nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'zdravoe_contact_nonce')) {
        wp_send_json_error(['message' => 'Неверный токен безопасности. Обновите страницу.'], 400);
    }

    // Honeypot (скрытое поле для ботов)
    $hp = isset($_POST['hp_field']) ? trim((string) $_POST['hp_field']) : '';
    if ($hp !== '') {
        // Притворяемся успехом
        wp_send_json_success(['message' => 'Спасибо! Сообщение отправлено.'], 200);
    }

    $name    = isset($_POST['name']) ? sanitize_text_field((string) $_POST['name']) : '';
    $email   = isset($_POST['email']) ? sanitize_email((string) $_POST['email']) : '';
    $message = isset($_POST['message']) ? wp_kses_post((string) $_POST['message']) : '';

    if ($name === '' || $email === '' || $message === '') {
        wp_send_json_error(['message' => 'Пожалуйста, заполните все поля.'], 422);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Укажите корректный Email.'], 422);
    }

    $to = defined('ZDRAVOE_CONTACT_EMAIL') && is_email(constant('ZDRAVOE_CONTACT_EMAIL'))
        ? constant('ZDRAVOE_CONTACT_EMAIL')
        : get_option('admin_email');

    $subject = 'Новое сообщение с сайта «Здравое мышление»';
    $body  = '<h2>Новое сообщение</h2>';
    $body .= '<p><strong>Имя:</strong> ' . esc_html($name) . '</p>';
    $body .= '<p><strong>Email:</strong> ' . esc_html($email) . '</p>';
    $body .= '<p><strong>Сообщение:</strong><br>' . nl2br(wp_kses_post($message)) . '</p>';

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'Reply-To: ' . esc_html($name) . ' <' . $email . '>',
    ];

    $sent = wp_mail($to, $subject, $body, $headers);
    if ($sent) {
        wp_send_json_success(['message' => 'Спасибо! Ваше сообщение отправлено.'], 200);
    } else {
        wp_send_json_error([
            'message' => 'Не удалось отправить письмо. Проверьте настройки почты на хостинге или установите SMTP-плагин.',
        ], 500);
    }
}


// Регистрируем кастомные типы записей
function zdravoe_register_cpts() {
    // Тип записи: Книга
    register_post_type('book', [
        'labels' => [
            'name' => 'Книги',
            'singular_name' => 'Книга',
            'add_new' => 'Добавить книгу',
            'add_new_item' => 'Новая книга',
            'edit_item' => 'Редактировать книгу',
            'new_item' => 'Новая книга',
            'view_item' => 'Посмотреть книгу',
            'search_items' => 'Искать книги',
            'not_found' => 'Не найдено',
            'not_found_in_trash' => 'В корзине не найдено',
        ],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'has_archive' => 'books',
        'query_var' => 'book',
        'rewrite' => [
            'slug' => 'books',
            'with_front' => false,
            'feeds' => false,
            'pages' => true,
            'ep_mask' => EP_PERMALINK,
        ],
        'menu_icon' => 'dashicons-book-alt',
        'supports' => ['title', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ]);

    // Тип записи: Статья
    register_post_type('article', [
        'labels' => [
            'name' => 'Статьи',
            'singular_name' => 'Статья',
            'add_new' => 'Добавить статью',
            'add_new_item' => 'Новая статья',
            'edit_item' => 'Редактировать статью',
            'new_item' => 'Новая статья',
            'view_item' => 'Посмотреть статью',
            'search_items' => 'Искать статьи',
            'not_found' => 'Не найдено',
            'not_found_in_trash' => 'В корзине не найдено',
        ],
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'has_archive' => 'articles',
        'query_var' => 'article',
        'rewrite' => [
            'slug' => 'articles',
            'with_front' => false,
            'feeds' => false,
            'pages' => true,
            'ep_mask' => EP_PERMALINK,
        ],
        'menu_icon' => 'dashicons-media-text',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'taxonomies' => ['article_category'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'zdravoe_register_cpts');

// Флашим правила один раз после активации темы и при первом заходе в админку
add_action('after_switch_theme', function () {
    zdravoe_register_cpts();
    if (function_exists('zdravoe_register_taxonomies')) {
        zdravoe_register_taxonomies();
    }
    flush_rewrite_rules();
});

add_action('admin_init', function () {
    if (!get_option('zdravoe_rewrite_flushed')) {
        flush_rewrite_rules();
        update_option('zdravoe_rewrite_flushed', 1);
    }
});

// Регистрация таксономии категорий статей
function zdravoe_register_taxonomies() {
    $labels = [
        'name' => 'Категории статей',
        'singular_name' => 'Категория статьи',
        'search_items' => 'Искать категории',
        'all_items' => 'Все категории',
        'parent_item' => 'Родительская категория',
        'parent_item_colon' => 'Родительская категория:',
        'edit_item' => 'Редактировать категорию',
        'update_item' => 'Обновить категорию',
        'add_new_item' => 'Добавить категорию',
        'new_item_name' => 'Новая категория',
        'menu_name' => 'Категории',
    ];
    register_taxonomy('article_category', ['article'], [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => [ 'slug' => 'article-category', 'with_front' => false ],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'zdravoe_register_taxonomies');
// Устанавливаем 12 записей на страницу для архивов статей и их категорий
add_action('pre_get_posts', function ($q) {
    if (is_admin() || !$q->is_main_query()) return;
    if ($q->is_post_type_archive('article') || $q->is_tax('article_category')) {
        $q->set('posts_per_page', 12);
    }
});

// AJAX: ленивое получение карточек статей
add_action('wp_ajax_nopriv_zdravoe_fetch_articles', 'zdravoe_fetch_articles');
add_action('wp_ajax_zdravoe_fetch_articles', 'zdravoe_fetch_articles');
function zdravoe_fetch_articles() {
    $nonce_ok = (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'zdravoe_articles_nonce'));
    // Запрос только на чтение публичных данных — не блокируем при невалидном nonce
    // (например, после смены домена/кэша). Просто продолжаем с безопасными ограничениями.

    $ppp   = isset($_POST['ppp']) ? max(1, min(30, (int)$_POST['ppp'])) : 12;
    $offset= isset($_POST['offset']) ? max(0, (int)$_POST['offset']) : 0;
    $term  = isset($_POST['term']) ? sanitize_text_field((string)$_POST['term']) : 'all';

    $args = [
        'post_type'      => 'article',
        'post_status'    => 'publish',
        'posts_per_page' => $ppp,
        'offset'         => $offset,
        'no_found_rows'  => false,
    ];
    if ($term !== 'all' && $term !== '') {
        $term_id = (int)$term;
        if ($term_id > 0) {
            $args['tax_query'] = [[
                'taxonomy' => 'article_category',
                'field'    => 'term_id',
                'terms'    => [$term_id],
            ]];
        }
    }

    $q = new WP_Query($args);
    ob_start();
    $count = 0;
    if ($q->have_posts()) {
        while ($q->have_posts()) { $q->the_post(); $count++;
            $term_ids = wp_get_object_terms(get_the_ID(), 'article_category', ['fields' => 'ids']);
            if (is_wp_error($term_ids)) { $term_ids = []; }
            $terms_attr = $term_ids ? implode(',', array_map('intval', $term_ids)) : '';

            $custom = get_post_meta(get_the_ID(), 'zdravoe_article_content', true);
            $source = $custom ? $custom : get_post_field('post_content', get_the_ID());
            $rt = function_exists('zdravoe_reading_time') ? zdravoe_reading_time($source) : ['minutes'=>0,'words'=>0];
            ?>
            <a href="<?php the_permalink(); ?>" data-article-card data-terms="<?php echo esc_attr($terms_attr); ?>" class="group block bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
              <div class="relative">
                <div class="absolute top-3 left-3 bg-white/95 px-3 py-1 rounded-full text-[0.75rem] text-gray-700 z-10">Article</div>
                <div class="aspect-[16/10] overflow-hidden bg-gray-100">
                  <?php
                    if (has_post_thumbnail()) {
                      the_post_thumbnail('article_card', ['class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300']);
                    } else {
                      $ph_file = get_template_directory() . '/assets/images/article_placeholder.png';
                      if (file_exists($ph_file)) {
                      $ph_url = get_template_directory_uri() . '/assets/images/article_placeholder.png?ver=' . filemtime($ph_file);
                      echo '<img src="' . esc_url($ph_url) . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async" />';
                      } else {
                        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500"><rect width="100%" height="100%" fill="#e5e7eb"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="sans-serif" font-size="20">Нет обложки</text></svg>';
                        $data_url = 'data:image/svg+xml;base64,' . base64_encode($svg);
                        echo '<img src="' . $data_url . '" alt="' . esc_attr(get_the_title()) . '" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />';
                      }
                    }
                  ?>
                </div>
              </div>
              <div class="p-6">
                <h3 class="serif text-[1.25rem] font-[700] text-gray-900 mb-2 line-clamp-2"><?php the_title(); ?></h3>
                <p class="text-gray-600 mb-4 line-clamp-2"><?php echo esc_html( get_post_meta(get_the_ID(), 'zdravoe_article_description', true) ?: get_the_excerpt() ); ?></p>
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100 text-sm text-gray-500">
                  <?php echo get_the_date('j F Y'); ?>
                  <span class="mx-1">·</span>
                  <span class="inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    ~<?php echo (int)$rt['minutes']; ?> мин
                  </span>
                </div>
              </div>
            </a>
            <?php
        }
        wp_reset_postdata();
    }
    $html = ob_get_clean();
    $has_more = ($q->found_posts > ($offset + $count));
    wp_send_json_success([
        'html' => $html,
        'count' => (int)$count,
        'has_more' => (bool)$has_more,
    ]);
}

// Если правило /books/ отсутствует (например, после смены структуры ЧПУ) — мягко перезапишем правила
add_action('init', function () {
    $rules = get_option('rewrite_rules');
    if (!is_array($rules) || (!array_key_exists('books/?$', $rules) && !array_key_exists('books/([0-9]{1,})/?$', $rules))) {
        flush_rewrite_rules(false);
    }
});

// Убираем редактор для книг и добавляем метаблоки (Название остаётся стандартным полем WP)
add_action('admin_init', function () {
    remove_post_type_support('book', 'editor');
    remove_post_type_support('article', 'editor');
});

// Скрипт для админки книг (репитер глав)
add_action('admin_enqueue_scripts', function ($hook) {
    $screen = get_current_screen();
    if (!$screen) return;

    if ($screen->post_type === 'book') {
        // редактор для полей глав
        if (function_exists('wp_enqueue_editor')) {
            wp_enqueue_editor();
        }
        wp_enqueue_script(
            'zdravoe-admin-book',
            get_template_directory_uri() . '/assets/admin-book.js',
            ['jquery', 'wp-editor', 'quicktags'],
            filemtime(get_template_directory() . '/assets/admin-book.js'),
            true
        );
    }

    if ($screen->post_type === 'article') {
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        }
    }
});

// Метабокс «Детали книги»
add_action('add_meta_boxes', function () {
    // Кадрирование обложки статьи (Cropper)
    add_meta_box(
        'zdravoe_article_crop',
        'Кадрирование обложки (16:9 и 16:10)',
        function ($post) {
            $thumb_id = get_post_thumbnail_id($post->ID);
            $img_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
            ?>
            <style>
              .zdravoe-crop-wrap { border:1px solid #e5e5e5; border-radius:8px; background:#f3f4f6; padding:8px; }
              .zdravoe-crop-controls { display:flex; gap:6px; margin:8px 0; }
              .zdravoe-crop-canvas { max-width:100%; max-height:360px; background:#fff; }
            </style>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div>
                <div style="margin-bottom:6px;font-weight:600;">Страница статьи (16:9)</div>
                <div class="zdravoe-crop-wrap">
                  <img id="zdravoe-crop-hero" class="zdravoe-crop-canvas" src="<?php echo esc_url($img_url); ?>" alt="article-hero-crop" />
                </div>
                <div class="zdravoe-crop-controls">
                  <button type="button" class="button" data-crop-zoom="hero,-0.1">–</button>
                  <button type="button" class="button" data-crop-zoom="hero,0.1">+</button>
                  <button type="button" class="button button-primary" data-crop-save="hero" data-post-id="<?php echo (int)$post->ID; ?>">Сохранить кроп 16:9</button>
                </div>
              </div>
              <div>
                <div style="margin-bottom:6px;font-weight:600;">Карточка статьи (16:10)</div>
                <div class="zdravoe-crop-wrap">
                  <img id="zdravoe-crop-card" class="zdravoe-crop-canvas" src="<?php echo esc_url($img_url); ?>" alt="article-card-crop" />
                </div>
                <div class="zdravoe-crop-controls">
                  <button type="button" class="button" data-crop-zoom="card,-0.1">–</button>
                  <button type="button" class="button" data-crop-zoom="card,0.1">+</button>
                  <button type="button" class="button button-primary" data-crop-save="card" data-post-id="<?php echo (int)$post->ID; ?>">Сохранить кроп 16:10</button>
                </div>
              </div>
            </div>
            <p class="description" style="margin-top:8px;">Выберите область кадра (можно перемещать и масштабировать). Сохраните отдельно для каждой пропорции.</p>
            <?php
        },
        'article',
        'side',
        'low'
    );
    // Предпросмотр обложки статьи в нужных пропорциях + выбор фокуса
    /* add_meta_box(
        'zdravoe_article_preview',
        'Предпросмотр обложки',
        function ($post) {
            $thumb_id = get_post_thumbnail_id($post->ID);
            $img_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
            $fx = get_post_meta($post->ID, 'zdravoe_article_focus_x', true);
            $fy = get_post_meta($post->ID, 'zdravoe_article_focus_y', true);
            if ($fx === '' || !is_numeric($fx)) $fx = 50;
            if ($fy === '' || !is_numeric($fy)) $fy = 50;
            ?>
            <style>
              .zdravoe-ratio { position: relative; width: 100%; overflow: hidden; border: 1px solid #e5e5e5; border-radius: 8px; background: #f3f4f6; }
              .zdravoe-ratio img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
              .zdravoe-ratio-16x9 { padding-top: 56.25%; }
              .zdravoe-ratio-16x10 { padding-top: 62.5%; }
              .zdravoe-focus-dot { position:absolute; width:10px; height:10px; border-radius:50%; background:#f5c542; border:2px solid #8A6A5B; transform:translate(-50%,-50%); pointer-events:none; }
            </style>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div>
                <div style="margin-bottom:6px;font-weight:600;">Страница статьи (16:9)</div>
                <div class="zdravoe-ratio zdravoe-ratio-16x9" data-focal-container>
                  <img data-preview-hero src="<?php echo esc_url($img_url); ?>" alt="preview-article-hero" style="<?php echo 'object-position:' . esc_attr($fx) . '% ' . esc_attr($fy) . '%;'; ?>" />
                  <span class="zdravoe-focus-dot" data-focus-dot style="left:<?php echo (float)$fx; ?>%;top:<?php echo (float)$fy; ?>%;"></span>
                </div>
              </div>
              <div>
                <div style="margin-bottom:6px;font-weight:600;">Карточка статьи (16:10)</div>
                <div class="zdravoe-ratio zdravoe-ratio-16x10" data-focal-container>
                  <img data-preview-card src="<?php echo esc_url($img_url); ?>" alt="preview-article-card" style="<?php echo 'object-position:' . esc_attr($fx) . '% ' . esc_attr($fy) . '%;'; ?>" />
                  <span class="zdravoe-focus-dot" data-focus-dot style="left:<?php echo (float)$fx; ?>%;top:<?php echo (float)$fy; ?>%;"></span>
                </div>
              </div>
            </div>
            <input type="hidden" id="zdravoe_article_focus_x" name="zdravoe_article_focus_x" value="<?php echo esc_attr($fx); ?>" />
            <input type="hidden" id="zdravoe_article_focus_y" name="zdravoe_article_focus_y" value="<?php echo esc_attr($fy); ?>" />
            <p class="description" style="margin-top:8px;">Щёлкните по превью, чтобы задать «фокус». Предпросмотр обновляется сразу после выбора обложки, без сохранения записи.</p>
            <?php
        },
        'article',
        'side',
        'low'
    ); */
    add_meta_box(
        'zdravoe_book_meta',
        'Детали книги',
        function ($post) {
            wp_nonce_field('zdravoe_book_meta', 'zdravoe_book_meta_nonce');

            $desc = get_post_meta($post->ID, 'zdravoe_book_description', true);
            $chapters = get_post_meta($post->ID, 'zdravoe_book_chapters', true);
            if (!is_array($chapters)) $chapters = [];
            ?>
            <p>
                <label for="zdravoe_book_description"><strong>Описание книги</strong> (необязательно)</label>
                <textarea id="zdravoe_book_description" name="zdravoe_book_description" rows="3" style="width:100%;" placeholder="Короткое описание книги..."><?php echo esc_textarea((string)$desc); ?></textarea>
            </p>

            <hr />
            <h4>Главы книги</h4>
            <p class="description">Добавьте одну или больше глав. Каждая глава содержит заголовок и текст.</p>

            <div id="zdravoe-book-chapters" data-index="<?php echo count($chapters); ?>">
                <?php if ($chapters): foreach ($chapters as $i => $ch):
                    $title = isset($ch['title']) ? (string)$ch['title'] : '';
                    $content = isset($ch['content']) ? (string)$ch['content'] : '';
                ?>
                <div class="zdravoe-chapter-item" style="border:1px solid #e5e5e5;padding:12px;border-radius:6px;margin-bottom:10px;background:#fff;">
                    <p>
                        <label><strong>Заголовок главы</strong></label>
                        <input type="text" name="zdravoe_book_chapters[<?php echo (int)$i; ?>][title]" value="<?php echo esc_attr($title); ?>" style="width:100%;" />
                    </p>
                    <p>
                        <label><strong>Текст главы</strong></label>
                        <?php
                          $editor_id = 'zdravoe_book_chapter_' . (int)$i;
                          wp_editor(
                            (string)$content,
                            $editor_id,
                            [
                              'textarea_name' => 'zdravoe_book_chapters[' . (int)$i . '][content]',
                              'media_buttons' => true,
                              'teeny' => false,
                              'quicktags' => true,
                              'editor_height' => 280,
                            ]
                          );
                        ?>
                    </p>
                    <p>
                        <button type="button" class="button button-secondary zdravoe-chapter-remove">Удалить главу</button>
                    </p>
                </div>
                <?php endforeach; endif; ?>
            </div>
            <p>
                <button type="button" class="button button-primary" id="zdravoe-book-add">Добавить главу</button>
            </p>

            <script type="text/template" id="zdravoe-book-chapter-tpl">
                <div class="zdravoe-chapter-item" style="border:1px solid #e5e5e5;padding:12px;border-radius:6px;margin-bottom:10px;background:#fff;">
                    <p>
                        <label><strong>Заголовок главы</strong></label>
                        <input type="text" name="zdravoe_book_chapters[__INDEX__][title]" value="" style="width:100%;" />
                    </p>
                    <p>
                        <label><strong>Текст главы</strong></label>
                        <textarea id="zdravoe_book_chapter___INDEX__" class="zdravoe-editor" name="zdravoe_book_chapters[__INDEX__][content]" rows="6" style="width:100%;" placeholder="Текст главы..."></textarea>
                    </p>
                    <p>
                        <button type="button" class="button button-secondary zdravoe-chapter-remove">Удалить главу</button>
                    </p>
                </div>
            </script>
            <?php
        },
        'book',
        'normal',
        'high'
    );
});

// Сохранение метаданных книги
add_action('save_post_book', function ($post_id, $post) {
    if (!isset($_POST['zdravoe_book_meta_nonce']) || !wp_verify_nonce($_POST['zdravoe_book_meta_nonce'], 'zdravoe_book_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Описание
    $desc = isset($_POST['zdravoe_book_description']) ? sanitize_textarea_field((string)$_POST['zdravoe_book_description']) : '';
    update_post_meta($post_id, 'zdravoe_book_description', $desc);

    // Главы
    $chapters = isset($_POST['zdravoe_book_chapters']) && is_array($_POST['zdravoe_book_chapters']) ? $_POST['zdravoe_book_chapters'] : [];
    $clean = [];
    foreach ($chapters as $ch) {
        $title = isset($ch['title']) ? sanitize_text_field((string)$ch['title']) : '';
        $content = isset($ch['content']) ? wp_kses_post((string)$ch['content']) : '';
        if ($title === '' && trim(strip_tags($content)) === '') continue; // пропускаем пустые
        $clean[] = [
            'title' => $title,
            'content' => $content,
        ];
    }
    update_post_meta($post_id, 'zdravoe_book_chapters', $clean);
}, 10, 2);

// -------- Статьи: метабокс «Детали статьи» (описание), требование названия --------

add_action('add_meta_boxes', function () {
    // Редактор статьи (вместо стандартного)
    add_meta_box(
        'zdravoe_article_editor',
        'Текст статьи',
        function ($post) {
            wp_nonce_field('zdravoe_article_editor', 'zdravoe_article_editor_nonce');
            $content = get_post_meta($post->ID, 'zdravoe_article_content', true);
            wp_editor(
                (string)$content,
                'zdravoe_article_content_editor',
                [
                    'textarea_name' => 'zdravoe_article_content',
                    'media_buttons' => true,
                    'teeny' => false,
                    'quicktags' => true,
                    'editor_height' => 320,
                ]
            );
        },
        'article',
        'normal',
        'high'
    );

    add_meta_box(
        'zdravoe_article_meta',
        'Детали статьи',
        function ($post) {
            wp_nonce_field('zdravoe_article_meta', 'zdravoe_article_meta_nonce');
            $desc = get_post_meta($post->ID, 'zdravoe_article_description', true);
            ?>
            <p>
                <label for="zdravoe_article_description"><strong>Описание статьи</strong> (необязательно)</label>
                <textarea id="zdravoe_article_description" name="zdravoe_article_description" rows="3" style="width:100%;" placeholder="Короткое описание статьи..."><?php echo esc_textarea((string)$desc); ?></textarea>
            </p>
            <p class="description">Текст статьи вводите в основном редакторе сверху (поддерживает заголовки, цитаты, изображения, видео и пр.). Обложку добавьте в «Изображение записи» справа.</p>
            <?php
        },
        'article',
        'normal',
        'high'
    );
});

add_action('save_post_article', function ($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Описание
    if (isset($_POST['zdravoe_article_meta_nonce']) && wp_verify_nonce($_POST['zdravoe_article_meta_nonce'], 'zdravoe_article_meta')) {
        $desc = isset($_POST['zdravoe_article_description']) ? sanitize_textarea_field((string)$_POST['zdravoe_article_description']) : '';
        update_post_meta($post_id, 'zdravoe_article_description', $desc);
    }

    // Контент из нашего редактора
    if (isset($_POST['zdravoe_article_editor_nonce']) && wp_verify_nonce($_POST['zdravoe_article_editor_nonce'], 'zdravoe_article_editor')) {
        $content = isset($_POST['zdravoe_article_content']) ? (string)$_POST['zdravoe_article_content'] : '';
        $content = wp_kses_post($content);
        update_post_meta($post_id, 'zdravoe_article_content', $content);
    }
}, 10, 2);

// Требуем название статьи при публикации/отправке
add_filter('wp_insert_post_data', function ($data, $postarr) {
    if (($data['post_type'] ?? '') === 'article') {
        $status = $data['post_status'] ?? '';
        $title  = isset($data['post_title']) ? trim((string)$data['post_title']) : '';
        if (in_array($status, ['publish', 'pending', 'future'], true) && $title === '') {
            $data['post_status'] = 'draft';
            add_filter('redirect_post_location', function ($loc) {
                return add_query_arg('zdravoe_article_title_required', 1, $loc);
            });
        }
    }
    return $data;
}, 10, 2);

add_action('admin_notices', function () {
    if (!empty($_GET['zdravoe_article_title_required'])) {
        echo '<div class="notice notice-error is-dismissible"><p>Для статьи обязательно заполните поле «Название».</p></div>';
    }
});

// === Reading time helpers ===
if (!function_exists('zdravoe_reading_time')) {
    /**
     * Estimate reading time in minutes based on text/html content.
     * @param string $html
     * @param int $wpm Words per minute (default ~220 for RU)
     * @return array{minutes:int, words:int}
     */
    function zdravoe_reading_time($html, $wpm = 220)
    {
        if (!is_string($html) || $html === '') {
            return ['minutes' => 1, 'words' => 0];
        }
        // Remove shortcodes and tags, decode entities
        $text = strip_tags(strip_shortcodes(html_entity_decode($html)));
        // Count words with unicode support
        if (preg_match_all('/[\p{L}\p{N}\']+/u', $text, $m)) {
            $words = count($m[0]);
        } else {
            // fallback: split by whitespace
            $parts = preg_split('/\s+/u', trim($text));
            $words = $parts ? count($parts) : 0;
        }
        $minutes = max(1, (int)ceil($words / max(120, (int)$wpm)));
        return ['minutes' => $minutes, 'words' => $words];
    }
}

// На всякий случай скрываем любые метабоксы предпросмотра/кроппинга обложек статей
add_action('do_meta_boxes', function () {
    remove_meta_box('zdravoe_article_preview', 'article', 'side');
    remove_meta_box('zdravoe_article_crop', 'article', 'side');
}, 100);

// Удалён AJAX для кропа изображений статей
