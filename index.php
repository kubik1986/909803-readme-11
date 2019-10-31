<?php

require_once 'init.php';

//TODO: Заменить переадресацию на feed.php
if (!empty($user)) {
    header('Location: popular.php');
    exit();
}

$page_content = include_template('main.php', []);
$layout_content = include_template('layout.php', array_merge($init_data, [
    'title' => $config['sitename'].': блог, каким он должен быть',
    'content' => $page_content,
    'include_dz_scripts' => false,
    'include_main_script' => false,
]));
echo $layout_content;
