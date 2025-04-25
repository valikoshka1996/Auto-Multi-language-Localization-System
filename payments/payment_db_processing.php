<?php

function savePaymentToDatabase($db_host, $db_user, $db_password, $db_name, $paymentData) {
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO payments (
                    order_id, status, amount, paytype, created_date,
                    sender_phone, sender_email, sender_first_name,
                    sender_last_name, payment_id
                ) VALUES (
                    :order_id, :status, :amount, :paytype, :created_date,
                    :sender_phone, :sender_email, :sender_first_name,
                    :sender_last_name, :payment_id
                )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':order_id'         => $paymentData['order_id'],
            ':status'           => $paymentData['status'],
            ':amount'           => $paymentData['amount'],
            ':paytype'          => $paymentData['paytype'],
            ':created_date'     => $paymentData['created_date'],
            ':sender_phone'     => $paymentData['sender_phone'],
            ':sender_email'     => $paymentData['sender_email'],
            ':sender_first_name'=> $paymentData['sender_first_name'],
            ':sender_last_name' => $paymentData['sender_last_name'],
            ':payment_id'       => $paymentData['payment_id'],
        ]);

    } catch (PDOException $e) {
        file_put_contents('db_errors.log', date('Y-m-d H:i:s') . " | DB Error: " . $e->getMessage() . "\n", FILE_APPEND);
    }
}


?>