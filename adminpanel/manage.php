<?php
include 'assets/nav.php';
session_start();

$adminFile = __DIR__ . '/assets/admin.json';
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $adminData = json_decode(file_get_contents($adminFile), true);
    $storedHash = $adminData['password'] ?? '';

    if (!password_verify($oldPassword, $storedHash)) {
        $messages[] = ['type' => 'danger', 'text' => '❌ Старий пароль невірний.'];
    } elseif ($newPassword !== $confirmPassword) {
        $messages[] = ['type' => 'danger', 'text' => '❌ Нові паролі не співпадають.'];
    } elseif (strlen($newPassword) < 6) {
        $messages[] = ['type' => 'danger', 'text' => '⚠️ Новий пароль має бути не менше 6 символів.'];
    } else {
        $adminData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        file_put_contents($adminFile, json_encode($adminData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $messages[] = ['type' => 'success', 'text' => '✅ Пароль успішно оновлено!'];
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <title>Change Admin Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: "Segoe UI", sans-serif;
    }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    .form-label {
      font-weight: 500;
    }

    .btn-primary {
      border-radius: 8px;
      padding: 10px 20px;
    }

    .container {
      max-width: 460px;
      margin-top: 60px;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="card p-4">
      <h4 class="mb-4 text-center">🔒 Зміна пароля адміністратора</h4>

      <?php foreach ($messages as $msg): ?>
        <div class="alert alert-<?= $msg['type'] ?>"><?= $msg['text'] ?></div>
      <?php endforeach; ?>

      <form method="post" autocomplete="off">
        <div class="mb-3">
          <label class="form-label" for="old_password">Старий пароль</label>
          <input type="password" name="old_password" id="old_password" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label" for="new_password">Новий пароль</label>
          <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>

        <div class="mb-4">
          <label class="form-label" for="confirm_password">Повторіть новий пароль</label>
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Зберегти новий пароль</button>
      </form>
    </div>
  </div>

</body>
</html>
