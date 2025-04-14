<?php
// Перевірка наявності сесії (якщо вже увійшли)
session_start();

// Якщо користувач вже авторизований, не потрібно виконувати авторизацію
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: admin.php"); // Перенаправлення на сторінку після входу
    exit;
}

// Функція для перевірки логіну та пароля
function check_login($login, $password) {
    // Завантажуємо дані з admin.json
    $admin_data = json_decode(file_get_contents('admin.json'), true);

    if ($admin_data['login'] === $login && $admin_data['password'] === $password) {
        return true;
    }

    return false;
}

// Обробка форми входу
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if (check_login($login, $password)) {
        // Якщо логін та пароль правильні, зберігаємо сесію
        session_start();
        $_SESSION['logged_in'] = true;
        header("Location: admin.php"); // Перенаправлення на головну сторінку після авторизації
        exit;
    } else {
        $error = "Invalid login or password!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="login">Login:</label>
                <input type="text" name="login" id="login" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
