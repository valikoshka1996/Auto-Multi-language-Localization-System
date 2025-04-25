<?php
require_once '../config.php';
require_once 'payment_db_processing.php';

$data = $_POST['data'] ?? '';
$signature = $_POST['signature'] ?? '';
$expected = base64_encode(sha1($private_key . $data . $private_key, true));

if ($signature !== $expected) {
    http_response_code(403);
    exit('Invalid signature');
}

$decoded = json_decode(base64_decode($data), true);
$order_id = $decoded['order_id'] ?? null;

//ротація токенів
if (($decoded['status'] ?? '') === 'success' && $order_id) {
        // 📦 ОЧИЩЕННЯ ПАПКИ
        $dir = '../tokens/';
        $files = glob($dir . '*');

        if (count($files) > 100) {
        // Сортуємо за часом створення (від найстаріших)
        usort($files, function($a, $b) {
            return filemtime($a) <=> filemtime($b);
        });

        // Видаляємо перші 50
        $to_delete = array_slice($files, 0, 50);
        foreach ($to_delete as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
    file_put_contents("../tokens/paid_$order_id.txt", 'paid');
}

$decoded_data = json_decode(base64_decode($data), true);
$order_id = $decoded_data['order_id'] ?? null;
$status = $decoded_data['status'] ?? null;
$amount = $decoded_data['amount'] ?? null;
$paytype = $decoded_data['paytype'] ?? null;
$created_date = $decoded_data['create_date'] ?? date('Y-m-d H:i:s');
$sender_phone = $decoded_data['sender_phone'] ?? null;
$sender_email = $decoded_data['sender_email'] ?? null;
$first_name  = $decoded_data['sender_first_name'] ?? null;
$last_name = $decoded_data['sender_last_name'] ?? null;
$payment_id = $decoded_data['payment_id'] ?? null;

// Перевірка підпису
$check_signature = base64_encode(sha1($private_key . $data . $private_key, true));

if ($signature === $check_signature) {
    // Лог у файл (опціонально)
    file_put_contents('payments.log', date('Y-m-d H:i:s') . " | ORDER_ID: $order_id | STATUS: $status\n", FILE_APPEND);

    if ($status === 'success') {
        $paymentData = [
            'order_id'     => $order_id,
            'status'       => $status,
            'amount'       => $amount,
            'paytype'      => $paytype,
            'created_date' => $created_date,
            'sender_phone' => $sender_phone,
            'sender_email' => $sender_email,
            'sender_first_name' => $first_name,
            'sender_last_name' => $last_name,
            'payment_id' => $payment_id
            
        ];

        $jsonFile = 'payments.json';

        if (file_exists($jsonFile)) {
            $existing = json_decode(file_get_contents($jsonFile), true);
            if (!is_array($existing)) $existing = [];
        } else {
            $existing = [];
        }

        $existing[] = $paymentData;
        file_put_contents($jsonFile, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        savePaymentToDatabase($host, $db_user, $db_password, $dbName, $paymentData);
    }
}
