<?php

require_once 'init.php';

if (empty($user)) {
    header('Location: /');
    exit();
}

$content_types = [
    [
        'title' => 'Фото',
        'icon_class' => 'photo',
    ],
    [
        'title' => 'Видео',
        'icon_class' => 'video',
    ],
    [
        'title' => 'Текст',
        'icon_class' => 'text',
    ],
    [
        'title' => 'Цитата',
        'icon_class' => 'quote',
    ],
    [
        'title' => 'Ссылка',
        'icon_class' => 'link',
    ],
];

$popular_posts = [
    [
        'date' => generate_random_date(0),
        'title' => 'Цитата',
        'type' => 'post-quote',
        'author_name' => 'Лариса',
        'author_avatar' => 'userpic-larisa.jpg',
        'author_id' => 2,
        'text' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'quote_author' => 'Неизвестный автор',
        'img' => null,
        'video' => null,
        'link' => null,
    ],
    [
        'date' => generate_random_date(1),
        'title' => 'Игра престолов',
        'type' => 'post-text',
        'author_name' => 'Владик',
        'author_avatar' => 'userpic.jpg',
        'author_id' => 1,
        'text' => 'Не могу дождаться начала финального сезона своего любимого сериала! Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsum illum odio quas aliquam ea maxime quaerat, ducimus architecto illo dignissimos a labore. Non dignissimos consectetur eveniet, ipsam vitae laudantium exercitationem modi tempore ipsum alias rem aspernatur laborum quod esse veritatis doloremque libero. Odio dolores eaque libero perspiciatis consequuntur adipisci culpa. Porro laudantium eius velit eaque nam aut necessitatibus? Cumque magni quod alias autem tenetur nemo modi magnam assumenda dicta suscipit neque repellendus, impedit beatae unde accusantium.',
        'quote_author' => null,
        'img' => null,
        'video' => null,
        'link' => null,
    ],
    [
        'date' => generate_random_date(2),
        'title' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'author_name' => 'Виктор',
        'author_avatar' => 'userpic-mark.jpg',
        'author_id' => 3,
        'text' => null,
        'quote_author' => null,
        'img' => 'rock-medium.jpg',
        'video' => null,
        'link' => null,
    ],
    [
        'date' => generate_random_date(3),
        'title' => 'Моя мечта',
        'type' => 'post-photo',
        'author_name' => 'Лариса',
        'author_avatar' => 'userpic-larisa.jpg',
        'author_id' => 2,
        'text' => null,
        'quote_author' => null,
        'img' => 'coast-medium.jpg',
        'video' => null,
        'link' => null,
    ],
    [
        'date' => generate_random_date(4),
        'title' => 'Лучшие курсы',
        'type' => 'post-link',
        'author_name' => 'Владик',
        'author_avatar' => 'userpic.jpg',
        'author_id' => 1,
        'text' => 'HTML Academy: интерактивные онлайн-курсы по HTML, CSS и JavaScript',
        'quote_author' => null,
        'img' => null,
        'video' => null,
        'link' => 'www.htmlacademy.ru',
    ],
];

$page_content = include_template('popular.php', array_merge($init_data, [
    'popular_posts' => $popular_posts,
    'max_preview_text_length' => $config['post']['max_preview_text_length'],
]));
$layout_content = include_template('layout.php', array_merge($init_data, [
    'title' => $config['sitename'].': популярное',
    'content' => $page_content,
    'user' => $user,
    'include_dz_scripts' => true,
    'include_main_script' => true,
]));
echo $layout_content;
