<?php
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 *
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array  $data Ассоциативный массив с данными для шаблона
 *
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = []): string
{
    $name = 'templates/'.$name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Определяет, является ли текущая страница главной.
 *
 * @return bool true, если текущая страница является главной, иначе false
 */
function check_main_page(): bool
{
    return isset($_SERVER['REQUEST_URI'])
                 && ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/index.php');
}

/**
 * Возвращает заголовок страницы по указанному адресу.
 *
 * @param string $url Адрес страницы
 *
 * @return string Заголовок страницы
 */
function get_page_title(string $url): string
{
    set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
        throw new ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
    }, E_WARNING);
    try {
        $page = file_get_contents($url);
    } catch (Exception $e) {
        $page = false;
    }
    restore_error_handler();

    if (!$page) {
        return null;
    }

    if (preg_match('/<title>(.+)<\/title>/', $page, $matches)) {
        return $matches[1];
    }

    return null;
}

/**
 * Обрезает строку с текстом, если ее длина превышает заданное количество символов.
 *
 * @param string $text   Строка с текстом
 * @param int    $length Максимальное число символов в строке
 *
 * @return string Строка с урезанным текстом
 */
function cut_text(string $text, int $length = 300): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    $words = explode(' ', $text);
    $current_length = 0;
    $i = 0;
    while (($current_length - 1) <= $length && isset($words[$i])) {
        $current_length += mb_strlen($words[$i]) + 1;
        ++$i;
    }
    $cutted_text = implode(' ', array_slice($words, 0, $i - 1)).'...';

    return $cutted_text;
}
