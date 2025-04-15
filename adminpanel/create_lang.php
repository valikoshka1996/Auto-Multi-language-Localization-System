<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

if (!isset($_POST['new_lang']) || empty($_POST['new_lang'])) {
    header("Location: admin.php");
    exit;
}

$newLang = strtolower(trim($_POST['new_lang']));
$targetFile = "../localisation/{$newLang}.txt";

// Якщо такий файл вже існує — редирект
if (file_exists($targetFile)) {
    header("Location: admin.php?lang={$newLang}");
    exit;
}

$existingFiles = glob('../localisation/*.txt');
$baseData = [];

// Якщо є хоч один файл — беремо ключі з нього
if (!empty($existingFiles)) {
    $randomFile = $existingFiles[array_rand($existingFiles)];
    $lines = file($randomFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($k, ) = explode('=', $line, 2);
            $baseData[trim($k)] = ''; // Значення порожнє
        }
    }

    $linesToWrite = [];
    foreach ($baseData as $key => $empty) {
        $linesToWrite[] = "{$key}=";
    }

    file_put_contents($targetFile, implode("\n", $linesToWrite));
} else {
    // Якщо взагалі немає жодного .txt — створюємо порожній
    file_put_contents($targetFile, "");
}

header("Location: admin.php?lang={$newLang}");
exit;
