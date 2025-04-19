<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_password_hash'])) {
    header("Location: index.php");
    exit;
}

$selectedLang = $_POST['lang'] ?? '';
$selectedLang = strtolower(trim($selectedLang));

$configPath = '../config.php';

if ($selectedLang && file_exists($configPath)) {
    $configContents = file_get_contents($configPath);
    $configContents = preg_replace(
        '/\$default_lang\s*=\s*[\'"][a-z]{2}[\'"]\s*;/',
        "\$default_lang = '{$selectedLang}';",
        $configContents
    );
    file_put_contents($configPath, $configContents);
}

header("Location: admin.php?lang={$selectedLang}");
exit;
