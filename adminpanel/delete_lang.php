<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_password_hash'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_POST['lang']) || empty($_POST['lang'])) {
    header("Location: admin.php");
    exit;
}

$lang = basename(trim($_POST['lang'])); // захист від directory traversal
$file = "../localisation/{$lang}.txt";

if (file_exists($file)) {
    unlink($file);
}

header("Location: admin.php");
exit;
