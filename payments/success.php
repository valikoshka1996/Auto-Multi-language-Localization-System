<?php
require_once '../config.php';
$order_id = $_GET['order_id'] ?? '';
$file = "../tokens/paid_$order_id.txt";

if (!file_exists($file)) {
    // Якщо файл не існує, редірект на головну
    header('Location: /');
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5;url=<?php echo $pay_success_url; ?>"> <!-- 5 секунд затримка перед редіректом -->
    <title>Перенаправлення...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 20%;
        }
        .message {
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="message">
        Зараз ви будете перенаправлені на сторінку курсу...
    </div>
</body>
</html>
