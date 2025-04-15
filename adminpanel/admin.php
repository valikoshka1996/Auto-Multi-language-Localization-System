<?php

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$langFiles = glob('../localisation/*.txt');
$langs = array_map(function ($file) {
    return basename($file, '.txt');
}, $langFiles);

$selectedLang = $_GET['lang'] ?? ($langs[0] ?? '');
$lines = file_exists("../localisation/{$selectedLang}.txt")
    ? file("../localisation/{$selectedLang}.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];

$entries = [];
foreach ($lines as $line) {
    if (strpos($line, '=') !== false) {
        list($k, $v) = explode('=', $line, 2);
        $entries[trim($k)] = trim($v);
    }
}
include 'assets/nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Localization Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Manage Localization</h1>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <form method="get" class="mb-3">
            <select name="lang" onchange="this.form.submit()" class="form-select w-auto">
                <?php foreach ($langs as $lang): ?>
                    <option value="<?= $lang ?>" <?= $lang === $selectedLang ? 'selected' : '' ?>><?= strtoupper($lang) ?></option>
                <?php endforeach; ?>
            </select>
        </form>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="entries">
                <?php foreach ($entries as $key => $val): ?>
                    <tr>
                        <td><input class="form-control" name="key[]" value="<?= htmlspecialchars($key) ?>"></td>
                        <td><input class="form-control" name="val[]" value="<?= htmlspecialchars($val) ?>"></td>
                        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Delete</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button class="btn btn-secondary mb-2" onclick="addRow()">Add Key</button>
        <form method="post" action="api.php">
            <input type="hidden" name="lang" value="<?= $selectedLang ?>">
            <div id="hidden-entries"></div>
            <button class="btn btn-primary" onclick="prepareSubmit(event)">Save Changes</button>
        </form>
    </div>

    <script>
        function addRow() {
            const row = `
            <tr>
                <td><input class="form-control" name="key[]"></td>
                <td><input class="form-control" name="val[]"></td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">Delete</button></td>
            </tr>`;
            document.getElementById('entries').insertAdjacentHTML('beforeend', row);
        }

        function prepareSubmit(e) {
            e.preventDefault();
            const form = e.target.closest('form');
            const hidden = document.getElementById('hidden-entries');
            hidden.innerHTML = '';

            document.querySelectorAll('#entries tr').forEach(row => {
                const key = row.querySelector('input[name="key[]"]').value;
                const val = row.querySelector('input[name="val[]"]').value;
                hidden.innerHTML += `<input type="hidden" name="key[]" value="${key}">`;
                hidden.innerHTML += `<input type="hidden" name="val[]" value="${val}">`;
            });

            form.submit();
        }
    </script>
</body>
</html>