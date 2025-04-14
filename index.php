<?php require_once 'lang_engine.php'; ?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $texts['title'] ?? 'Website' ?></title>
    <style>
        nav img {
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
</head>
<body>

<!-- Перемикач мов -->
<nav>
    <a href="?lang=en">
        <img src="flags/en.svg" alt="English" width="24"> English
    </a> |
    <a href="?lang=ua">
        <img src="flags/ua.svg" alt="Українська" width="24"> Українська
    </a>
</nav>

<hr>

<!-- Основний вміст -->
<h1><?= $texts['main'] ?? 'Main text missing' ?></h1>
<p><?= $texts['description'] ?? 'Description not found' ?></p>

<!-- Приклад форми -->
<form method="post">
    <label><?= $texts['form_name'] ?? 'Name' ?>:
        <input type="text" name="name">
    </label><br>
    <label><?= $texts['form_email'] ?? 'Email' ?>:
        <input type="email" name="email">
    </label><br>
    <button type="submit"><?= $texts['form_submit'] ?? 'Submit' ?></button>
</form>

</body>
</html>
