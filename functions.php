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

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел.
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int    $number Число, по которому вычисляем форму множественного числа
 * @param string $one    Форма единственного числа: яблоко, час, минута
 * @param string $two    Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many   Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod = $number % 100;
    if ($mod > 19) {
        $mod = $number % 10;
    }
    if ($mod === 1) {
        $result = $one;
    } elseif ($mod >= 2 && $mod <= 4) {
        $result = $two;
    } else {
        $result = $many;
    }

    return $result;
}

/**
 * Возвращает время в относительном формате, прошедшее с указанной даты.
 *
 * @param string $date Дата, оносительно которой высчитывается время
 *
 * @return string Отформатированное время добавления ставки
 */
function get_relative_time(string $date): string
{
    $result = '';
    $date = date_create($date);
    $now = date_create('now');
    if ($date > $now) {
        return 'Ошибка! Время больше текущего';
    }
    $diff = date_diff($date, date_create('now'));
    $total_days_passed = $diff->days;
    $total_weeks_passed = (int) floor($total_days_passed / 7);
    $years_passed = $diff->y;
    $months_passed = $diff->m;
    $days_passed = $diff->d;
    $hours_passed = $diff->h;
    $minutes_passed = $diff->i;
    $seconds_passed = $diff->s;

    if ($total_days_passed === 0) {
        if ($hours_passed === 0 && $minutes_passed === 0) {
            $result = 'Только что';
        } elseif ($hours_passed === 0) {
            $result = sprintf('%d %s назад', $minutes_passed, get_noun_plural_form($minutes_passed, 'минута', 'минуты', 'минут'));
        } else {
            $result = sprintf('%d %s назад', $hours_passed, get_noun_plural_form($hours_passed, 'час', 'часа', 'часов'));
        }
    } elseif ($total_weeks_passed === 0) {
        $result = sprintf('%d %s назад', $days_passed, get_noun_plural_form($days_passed, 'день', 'дня', 'дней'));
    } elseif ($total_weeks_passed < 5) {
        $result = sprintf('%d %s назад', $total_weeks_passed, get_noun_plural_form($total_weeks_passed, 'неделя', 'недели', 'недель'));
    } elseif ($years_passed === 0) {
        $result = sprintf('%d %s назад', $months_passed, get_noun_plural_form($months_passed, 'месяц', 'месяца', 'месяцев'));
    } elseif ($years_passed > 0 && $months_passed === 0) {
        $result = sprintf('%d %s назад', $years_passed, get_noun_plural_form($years_passed, 'год', 'года', 'лет'));
    } else {
        $result = sprintf('%d %s %d %s назад', $years_passed, get_noun_plural_form($years_passed, 'год', 'года', 'лет'), $months_passed, get_noun_plural_form($months_passed, 'месяц', 'месяца', 'месяцев'));
    }

    return $result;
}

/**
 * @param $index
 *
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}
