<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$lang = $_POST['lang'] ?? '';
$keys = $_POST['key'] ?? [];
$vals = $_POST['val'] ?? [];

if (!$lang) die("Missing lang");

// Перевірка на дублікати ключів
$trimmedKeys = array_map('trim', $keys);
$uniqueKeys = array_unique($trimmedKeys);

if (count($trimmedKeys) !== count($uniqueKeys)) {
    header("Location: admin.php?lang=$lang&error=duplicate_key");
    exit;
}


$localisationDir = '../localisation/';
$langFiles = glob($localisationDir . '*.txt');

// Побудова масиву ключів/значень для поточної мови
$dataCurrent = [];
foreach ($keys as $i => $k) {
    $k = trim($k);
    if ($k !== '') {
        $dataCurrent[$k] = trim($vals[$i] ?? '');
    }
}

// Збереження значень до активного файлу локалізації
$currentFilePath = "{$localisationDir}{$lang}.txt";
$currentFileContent = file_exists($currentFilePath)
    ? file($currentFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];

$currentEntries = [];
foreach ($currentFileContent as $line) {
    if (strpos($line, '=') !== false) {
        list($k, $v) = explode('=', $line, 2);
        $currentEntries[trim($k)] = trim($v);
    }
}

// Визначення видалених ключів
$existingKeys = array_keys($currentEntries);
$submittedKeys = array_keys($dataCurrent);
$deletedKeys = array_diff($existingKeys, $submittedKeys);

// Оновити значення активного файла локалізації
foreach ($dataCurrent as $k => $v) {
    $currentEntries[$k] = $v;
}

// Видалити ключі, які були стерті користувачем
foreach ($deletedKeys as $deletedKey) {
    unset($currentEntries[$deletedKey]);
}

// Записати поточний файл локалізації
file_put_contents($currentFilePath, implode(PHP_EOL, array_map(fn($k) => "$k={$currentEntries[$k]}", array_keys($currentEntries))));

// Оновлення всіх інших локалізацій
foreach ($langFiles as $filePath) {
    $fileLang = basename($filePath, '.txt');
    if ($fileLang === $lang) continue; // Пропустити активний

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $entries = [];

    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($k, $v) = explode('=', $line, 2);
            $entries[trim($k)] = trim($v);
        }
    }

    // Додати ключі, яких ще немає, з порожнім значенням
    foreach ($dataCurrent as $k => $_) {
        if (!array_key_exists($k, $entries)) {
            $entries[$k] = '';
        }
    }

    // Видалити ключі, які були стерті
    foreach ($deletedKeys as $deletedKey) {
        unset($entries[$deletedKey]);
    }

    // Записати назад
    $newContent = array_map(fn($k) => "$k={$entries[$k]}", array_keys($entries));
    file_put_contents($filePath, implode(PHP_EOL, $newContent));
}

header("Location: admin.php?lang=$lang&saved=1");
exit;

