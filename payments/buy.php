<?php
require_once '../config.php';

if(!$liqpay_integration){
    header('Location: /');
    exit;
}

$order_id = uniqid(); // Унікальний ID
$description = 'Доступ до курсу';

$data = [
    'public_key'   => $public_key,
    'version'      => '3',
    'action'       => 'pay',
    'amount'       => $pay_amount,
    'currency'     => $default_currency,
    'description'  => $description,
    'order_id'     => $order_id,
    'result_url'   => $site_url.'payments/success.php?order_id=' . $order_id,
    'server_url'   => $site_url.'payments/callback.php'
];

$data_encoded = base64_encode(json_encode($data));
$signature = base64_encode(sha1($private_key . $data_encoded . $private_key, true));
?>

<form id="liqpay-form" method="POST" action="https://www.liqpay.ua/api/3/checkout">
    <input type="hidden" name="data" value="<?= $data_encoded ?>" />
    <input type="hidden" name="signature" value="<?= $signature ?>" />
</form>

<script>
    document.getElementById("liqpay-form").submit();
</script>
