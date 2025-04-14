<?php
session_start();

$default_language = 'en';

function getRealIpAddr() {
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    return $_SERVER['REMOTE_ADDR'];
}

function getCountryByIp($ip) {
    $url = "https://get.geojs.io/v1/ip/country.json?ip=$ip";
    $response = @file_get_contents($url);

    if ($response === FALSE) {
        return 'en';
    }

    $data = json_decode($response, true);
    return isset($data[0]['country']) && $data[0]['country'] === 'UA' ? 'ua' : 'en';
}

function getLocalizationTexts($prefix) {
    $dir = __DIR__ . '/localisation';
    $files = array_filter(scandir($dir), function($file) use ($prefix) {
        return strpos($file, $prefix) === 0 && pathinfo($file, PATHINFO_EXTENSION) === 'txt';
    });

    $texts = [];
    foreach ($files as $file) {
        $key = substr($file, strlen($prefix), -4);
        $texts[$key] = file_get_contents($dir . '/' . $file);
    }

    return $texts;
}

function logAccess($ip, $lang) {
    $log_file = __DIR__ . '/logs/log.txt';
    $max_log_size = 10 * 1024 * 1024;

    if (file_exists($log_file) && filesize($log_file) >= $max_log_size) {
        file_put_contents($log_file, "");
    }

    $log_message = "[" . date('Y-m-d H:i:s') . "] IP: $ip, Language: $lang\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

// ====== Основна логіка ======

// Вибір мови вручну
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'ua'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Автоматичне визначення, якщо не вибрано вручну
if (!isset($_SESSION['lang'])) {
    $ip = getRealIpAddr();
    $_SESSION['lang'] = getCountryByIp($ip);
}


// Встановлюємо мову
$lang = $_SESSION['lang'];
$prefix = $lang . '_';
$texts = getLocalizationTexts($prefix);

// Для використання з інших файлів
$GLOBALS['lang'] = $lang;
$GLOBALS['texts'] = $texts;
logAccess(getRealIpAddr(), $lang);

