<?php
// general.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$config_path = '../config.php';
$localisation_path = '../localisation/';
$favicon_path = '../assets/favicon.png';

$config = file_get_contents($config_path);
$vars = [
    'liqpay_integration',
    'name',
    'default_lang',
    'action_url',
    'site_url',
    'service_mode',
    'default_currency',
    'db_user',
    'db_password',
    'host'
];

foreach ($vars as $var) {
    if (preg_match("/\\$$var\s*=\s*(.*?);/", $config, $matches)) {
        $values[$var] = trim(trim($matches[1]), "'\"");
    } else {
        $values[$var] = '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обробка завантаження favicon
    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
        if (strtolower($ext) === 'png') {
            move_uploaded_file($_FILES['favicon']['tmp_name'], $favicon_path);
        } else {
            echo "<div style='color: red'>❌ Лише .png дозволено для favicon</div>";
        }
    }

    $update_map = [
        'liqpay_integration' => isset($_POST['liqpay_integration']) ? 'true' : 'false',
        'name' => "'" . trim($_POST['name']) . "'",
        'default_lang' => "'" . trim($_POST['default_lang']) . "'",
        'action_url' => ($_POST['action_mode'] === 'default') ? "'payments/buy.php'" : "'" . trim($_POST['custom_action_url']) . "'",
        'site_url' => "'" . trim($_POST['site_url']) . "'",
        'service_mode' => isset($_POST['service_mode']) ? 'true' : 'false',
        'default_currency' => "'" . trim($_POST['default_currency']) . "'",
        'db_user' => "'" . trim($_POST['db_user']) . "'",
        'db_password' => "'" . trim($_POST['db_password']) . "'",
        'host' => "'" . trim($_POST['host']) . "'",
    ];

    foreach ($update_map as $key => $val) {
        if (preg_match("/\\$$key\s*=.*?;/", $config)) {
            $config = preg_replace("/(\\$$key\s*=).*?;/", "\$1 $val;", $config);
        } else {
            $config .= "\n\$$key = $val;";
        }
    }

    file_put_contents($config_path, $config);
    echo "<div style='color: green'>✅ Конфігурацію оновлено!</div>";
    header("Refresh:1");
    exit;
}

// Збір мов з папки localisation
$langs = [];
if (is_dir($localisation_path)) {
    foreach (scandir($localisation_path) as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'txt') {
            $langs[] = pathinfo($file, PATHINFO_FILENAME);
        }
    }
}

$currencies = ['UAH', 'USD', 'EUR'];
?>

<?php include 'assets/nav.php'; ?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>🔧 Налаштування сайту</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>

<div class="container">
<form method="POST" enctype="multipart/form-data">
    <div class="label-block">
        <label>Enable/Disable Payment</label>
        <label class="switch">
            <input type="checkbox" name="liqpay_integration" <?= $values['liqpay_integration'] === 'true' ? 'checked' : '' ?>>
            <span class="slider"></span>
        </label>
    </div>

    <div class="label-block">
        <label>Site Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($values['name']) ?>">
    </div>

    <div class="label-block">
        <label>Default Language</label>
        <select name="default_lang">
            <?php foreach ($langs as $lang): ?>
                <option value="<?= $lang ?>" <?= $lang == $values['default_lang'] ? 'selected' : '' ?>><?= strtoupper($lang) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

<div class="form-group">
    <label for="actionMode">Action URL</label>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="action_mode" id="defaultAction" value="default" <?= $values['action_url'] === 'payments/buy.php' ? 'checked' : '' ?>>
        <label class="form-check-label" for="defaultAction">Pay button</label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="action_mode" id="customAction" value="custom" <?= $values['action_url'] !== 'payments/buy.php' ? 'checked' : '' ?>>
        <label class="form-check-label" for="customAction">Custom URL</label>
    </div>
    <input type="text" class="form-control" id="customUrl" name="custom_action_url" placeholder="Your URL..." value="<?= htmlspecialchars($values['action_url'] !== 'payments/buy.php' ? $values['action_url'] : '') ?>" <?= $values['action_url'] === 'payments/buy.php' ? 'disabled' : '' ?>>
</div>

    <div class="label-block">
        <label>Site URL</label>
        <input type="text" name="site_url" value="<?= htmlspecialchars($values['site_url']) ?>">
    </div>

    <div class="label-block">
        <label>Service Mode</label>
        <label class="switch">
            <input type="checkbox" name="service_mode" <?= $values['service_mode'] === 'true' ? 'checked' : '' ?>>
            <span class="slider"></span>
        </label>
    </div>

    <div class="label-block">
        <label>Default Payment Currency</label>
        <select name="default_currency">
            <?php foreach ($currencies as $curr): ?>
                <option value="<?= $curr ?>" <?= $curr == $values['default_currency'] ? 'selected' : '' ?>><?= $curr ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="label-block">
        <label>Site Favicon (.png only)</label>
        <input type="file" name="favicon" accept=".png">
    </div>

    <hr>
    <h3>📦 Database</h3>

    <div class="label-block">
        <label>DB User</label>
        <input type="text" name="db_user" value="<?= htmlspecialchars($values['db_user']) ?>">
    </div>

    <div class="label-block">
        <label>DB Password</label>
        <input type="password" name="db_password" value="<?= htmlspecialchars($values['db_password']) ?>">
    </div>

    <div class="label-block">
        <label>Host</label>
        <input type="text" name="host" value="<?= htmlspecialchars($values['host']) ?>">
    </div>

    <button type="submit">💾 Зберегти зміни</button>
</form>
</div>
<script>
    // Додаємо обробник для перемикачів
    document.querySelectorAll('input[name="action_mode"]').forEach((input) => {
        input.addEventListener('change', function() {
            const customUrlInput = document.getElementById('customUrl');
            if (this.value === 'default') {
                customUrlInput.disabled = true;
            } else {
                customUrlInput.disabled = false;
            }
        });
    });

    // Початковий стан (якщо сторінка вже завантажена з перевіреним значенням)
    if (document.querySelector('input[name="action_mode"]:checked').value === 'default') {
        document.getElementById('customUrl').disabled = true;
    } else {
        document.getElementById('customUrl').disabled = false;
    }
</script>

</body>
</html>
