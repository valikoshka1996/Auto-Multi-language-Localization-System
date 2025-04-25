<?php
require_once '../config.php';

     // або 127.0.0.1
 // ім'я бази даних

// Підключення до MySQL
$conn = new mysqli($host, $db_user, $db_password);

// Перевірка підключення
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Створення бази даних
$sql = "CREATE DATABASE IF NOT EXISTS `$dbName`";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Підключення до щойно створеної бази
$conn->select_db($dbName);

// Створення таблиці `payments`
$sql = "CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(255),
    status VARCHAR(50),
    amount DECIMAL(10, 2),
    paytype VARCHAR(50),
    created_date BIGINT,
    sender_phone VARCHAR(20),
    sender_email VARCHAR(255),
    sender_first_name VARCHAR(100),
    sender_last_name VARCHAR(100),
    payment_id BIGINT
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'payments' created successfully";
} else {
    die("Error creating table: " . $conn->error);
}

$conn->close();
?>
