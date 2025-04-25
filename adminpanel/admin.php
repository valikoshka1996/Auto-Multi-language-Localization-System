<?php
session_start();
include_once '../config.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_password_hash'])) {
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
<script src="assets/admin.js"></script>
<body class="bg-light p-4">
    <div class="container">

        <form method="get" class="mb-3">
            <select name="lang" onchange="this.form.submit()" class="form-select w-auto">
                <?php foreach ($langs as $lang): ?>
                    <option value="<?= $lang ?>" <?= $lang === $selectedLang ? 'selected' : '' ?>><?= strtoupper($lang) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        
        <form method="post" action="create_lang.php" class="d-flex align-items-center gap-2 mb-3">
        <select name="new_lang" class="form-select w-auto">
           <?php
           $allPossibleLangs = ['en', 'fr', 'de', 'es', 'it', 'pl', 'ua', 'ru', 'uz', 'jp', 'cz', 'hu', 'ee', 'se', 'sk', 'pt', 'ae']; // список можливих мов
           $availableLangs = array_map('strtolower', $langs);
          $missingLangs = array_diff($allPossibleLangs, $availableLangs);
    
           foreach ($missingLangs as $lang) {
               echo "<option value=\"$lang\">" . strtoupper($lang) . "</option>";
            }
            ?>
      </select>
       <button type="submit" class="btn btn-success">Create localization</button>
    </form>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="toggleEditKeys">
            <label class="form-check-label" for="toggleEditKeys">Key editing access (Key)</label>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6">
             <input type="text" id="filterKey" class="form-control" placeholder="🔍 Key filter (Key)">
            </div>
            <div class="col-md-6">
              <input type="text" id="filterValue" class="form-control" placeholder="🔍 Value filter (Value)">
            </div>
        </div>

        
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
                        <td><input class="form-control editable-value" name="val[]" value="<?= htmlspecialchars($val) ?>"></td>

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
        <?php if ($selectedLang): ?>
            <?php if ($selectedLang === $default_lang): ?>
        <div class="alert alert-info w-auto d-inline-block">✅ Default language</div>
    <?php endif; ?>
        
    <form method="post" action="delete_lang.php" onsubmit="return confirm('Ви впевнені, що хочете видалити локалізацію <?= htmlspecialchars($selectedLang) ?>?')" class="mb-3">
        <input type="hidden" name="lang" value="<?= htmlspecialchars($selectedLang) ?>">
        <button type="submit" class="btn btn-danger">Delete Localization <?= strtoupper($selectedLang) ?></button>
    </form>
    
    <?php if ($selectedLang): ?>
    <form method="post" action="set_default.php" class="mb-3 d-inline-block">
        <input type="hidden" name="lang" value="<?= htmlspecialchars($selectedLang) ?>">
        <button type="submit" class="btn btn-warning">Set as Default</button>
    </form>

<?php endif; ?>

<?php endif; ?>
    </div>

    <script>
        function addRow() {
            const row = `
            <tr>
                <td><input class="form-control" name="key[]" required></td>
                <td><input class="form-control" name="val[]" required></td>
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
<button id="scrollTopBtn" class="btn btn-primary position-fixed rounded-circle" 
        style="bottom: 20px; right: 20px; display: none; z-index: 1055; width: 50px; height: 50px;">
  ↑
</button>
</body>
<?php if (isset($_GET['error']) && $_GET['error'] === 'duplicate_key'): ?>
<div class="alert alert-danger alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-3 shadow" role="alert" style="z-index: 1050; width: auto; max-width: 90%;">
  <strong>❌ Помилка:</strong> Знайдено дублікати ключів! Кожен ключ має бути унікальним.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<script>
  setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
      alert.classList.remove('show');
      setTimeout(() => alert.remove(), 500); // Чекаємо завершення анімації і прибираємо з DOM
    }
  }, 5000);
</script>
<?php endif; ?>

<?php if (isset($_GET['saved']) && $_GET['saved'] == 1): ?>
<div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 start-50 translate-middle-x mb-3 shadow" role="alert" style="z-index: 1050; width: auto; max-width: 90%;">
  ✅ Дані успішно збережено!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>


<script>
  setTimeout(() => {
    const alert = document.querySelector('.alert');
    if (alert) {
      alert.classList.remove('show');
      setTimeout(() => alert.remove(), 500); // Чекаємо завершення анімації і прибираємо з DOM
    }
  }, 5000);
</script>
<?php endif; ?>


</html>