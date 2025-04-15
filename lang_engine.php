<?php
session_start();

require_once 'config.php';

$default_language = $default_lang;

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
    if (isset($data[0]['country'])) {
    return strtolower($data[0]['country']) === 'ua' ? 'ua' : strtolower($data[0]['country']);
        } else {
    return 'unknown'; // або будь-яке значення за замовчуванням
}
}

function getLocalization($default_language, $prefix = 'en') {
    $file = __DIR__ . "/localisation/{$prefix}.txt";
    // Якщо файл для заданої мови не існує, використовується файл з дефолтною мовою
    if (!file_exists($file)) {
        $file = __DIR__ . "/localisation/{$default_language}.txt";
        if (!file_exists($file)) return [];
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $texts = [];
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $texts[trim($key)] = trim($val);
        }
    }
    return $texts;
}


function logAccess($ip, $lang) {
    $log_file = __DIR__ . '/logs/log.txt';
    $max_log_size = 10 * 1024 * 1024;

    if (file_exists($log_file) && filesize($log_file) >= $max_log_size) {
        file_put_contents($log_file, "");
    }
    date_default_timezone_set('Europe/Kiev');
    $log_message = "[" . date('Y-m-d H:i:s') . "] IP: $ip, Language: $lang\n";
    file_put_contents($log_file, $log_message, FILE_APPEND);
}

// ====== Основна логіка ======

// Вибір мови вручну
if (isset($_GET['lang']) && !empty($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Автоматичне визначення, якщо не вибрано вручну
$ip = getRealIpAddr();
if(isset($ip)){
   if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = getCountryByIp($ip);
} 
} else {
    $_SESSION['lang'] = $default_language;
}


// Встановлюємо мову
$lang = $_SESSION['lang'];
$prefix = $lang;
$texts = getLocalization($default_language, $prefix);

// Для використання з інших файлів
$GLOBALS['lang'] = $lang;
$GLOBALS['texts'] = $texts;
logAccess(getRealIpAddr(), $lang);

