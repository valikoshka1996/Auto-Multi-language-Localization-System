<?php
require_once '../config.php';


// Підключення до MySQL
$conn = new mysqli($host, $db_user, $db_password);

// Перевірка підключення
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Створення бази даних
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
if ($conn->query($sql) === TRUE) {
    echo "Database '$db_name' created successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Підключення до створеної бази
$conn->select_db($db_name);

// Створення таблиці `visits`
$sql = "CREATE TABLE IF NOT EXISTS visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(255),
    datetime VARCHAR(50),
    lang VARCHAR(10)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'visits' created successfully";
} else {
    die("Error creating table: " . $conn->error);
}

$conn->close();
?>
