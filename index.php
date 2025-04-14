<?php

// Отримуємо реальну IP-адресу користувача
function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

// Отримуємо країну за IP через GeoJS
function getCountryByIp($ip) {
    $url = "https://get.geojs.io/v1/ip/country.json?ip=$ip";
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        return 'en'; // За замовчуванням англійська
    }

    $data = json_decode($response, true);
    return isset($data[0]['country']) && $data[0]['country'] === 'UA' ? 'ua' : 'en';
}

// Функція для отримання текстів з файлів в масив
function getLocalizationTexts($prefix) {
    $dir = __DIR__ . '/localisation';
    $files = array_filter(scandir($dir), function($file) use ($prefix) {
        return strpos($file, $prefix) === 0 && pathinfo($file, PATHINFO_EXTENSION) === 'txt';
    });

    $texts = [];
    foreach ($files as $file) {
        $key = substr($file, strlen($prefix), -4); // Видаляємо префікс і розширення
        $texts[$key] = file_get_contents($dir . '/' . $file);
    }

    return $texts;
}

// Функція для запису логів з ротацією
function logAccess($ip, $country_code) {
    $log_file = __DIR__ . '/logs/log.txt';
    $max_log_size = 10 * 1024 * 1024; // 10 МБ

    // Перевірка на максимальний розмір логу
    if (file_exists($log_file) && filesize($log_file) >= $max_log_size) {
        file_put_contents($log_file, ""); // Очищаємо файл, якщо розмір перевищує ліміт
    }

    // Записуємо лог
    $log_message = "[" . date('Y-m-d H:i:s') . "] IP: $ip, Country: $country_code\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

// Основний код
$ip = getRealIpAddr();  // Отримуємо IP користувача
$country_code = getCountryByIp($ip);  // Отримуємо код країни
logAccess($ip, $country_code);  // Логуємо доступ

// Мова за замовчуванням
$default_language = 'en';

// Визначаємо префікс мови
$prefix = ($country_code === 'ua') ? 'ua_' : $default_language . '_';

// Отримуємо масив текстів для країни
$texts = getLocalizationTexts($prefix);

// Тепер можна використовувати тексти за ключами
echo "<h1>" . ($texts['main'] ?? 'Текст не знайдено') . "</h1>";
echo "<div>" . ($texts['div'] ?? 'Текст не знайдено') . "</div>";

?>
