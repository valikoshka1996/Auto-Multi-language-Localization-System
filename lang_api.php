<?php
session_start();
require_once 'lang_engine.php';

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];

    $prefix = $_GET['lang'];
    $texts = getLocalization($default_lang, $prefix);
    $GLOBALS['texts'] = $texts;

    echo json_encode($texts, JSON_UNESCAPED_UNICODE);
    exit;
}
