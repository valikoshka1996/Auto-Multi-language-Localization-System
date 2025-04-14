<?php require_once 'lang_engine.php'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $texts['title'] ?? 'Title' ?></title>
</head>
<body>
    <nav>
        <a href="?lang=en">English</a> | <a href="?lang=ua">Українська</a>
    </nav>

    <h1><?= $texts['main'] ?? 'Текст не знайдено' ?></h1>
    <div><?= $texts['div'] ?? 'Текст не знайдено' ?></div>
</body>
</html>
