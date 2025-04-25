<?php
require_once '../config.php';

function insertVisit($ip, $datetime, $lang) {
    global $host, $db_user, $db_password, $db_name;

    // Підключення до бази даних
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    // Перевірка з'єднання
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Підготовка SQL-запиту
    $stmt = $conn->prepare("INSERT INTO visits (ip, datetime, lang) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $ip, $datetime, $lang); // три рядки (string)

    // Виконання
    if ($stmt->execute()) {
        echo "Visit inserted successfully<br>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Закриття
    $stmt->close();
    $conn->close();
}
?>
