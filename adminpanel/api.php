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

$path = "../localisation/{$lang}.txt";
$data = [];

foreach ($keys as $i => $k) {
    if (trim($k) !== '') {
        $data[] = trim($k) . '=' . trim($vals[$i] ?? '');
    }
}

file_put_contents($path, implode(PHP_EOL, $data));

header("Location: admin.php?lang=$lang");
exit;