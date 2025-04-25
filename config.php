<?php

$name = 'TourBeat';
$default_lang = 'en';
$action_url = 'payments/buy.php';
$service_mode = false;
$default_currency = 'UAH';

if (!defined('FAVICON')) {
    define('FAVICON', 'assets/favicon.png');
}

$liqpay_integration = true;
$site_url = 'https://tourbeat.com.ua/';
$default_lang = 'en';

#liqpay integrations


$public_key = '';
$private_key = '';
$pay_success_url = '';
$pay_amount = 1;

#db config
$db_user = 'root';
$db_password  = '';
$host = 'localhost';

?>