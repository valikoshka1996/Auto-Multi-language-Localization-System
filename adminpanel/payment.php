<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_password_hash'])) {
    header("Location: index.php");
    exit;
}


require_once __DIR__ . '/fpdf/fpdf.php';
require_once 'assets/nav.php';
require_once '../config.php';
// Шлях до файлу конфігурації
$configPath = realpath(__DIR__ . '/../config.php');

// Якщо відправлено форму на оновлення
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_config'])) {
    // Зчитування існуючого вмісту конфігу
    $config_content = file_get_contents($configPath);

    // Створення асоціативного масиву з новими значеннями
    $replacements = [
        'public_key' => $_POST['public_key'] ?? $public_key,
        'private_key' => $_POST['private_key'] ?? $private_key,
        'pay_success_url' => $_POST['pay_success_url'] ?? $pay_success_url,
        'pay_amount' => $_POST['pay_amount'] ?? $pay_amount,
    ];

    foreach ($replacements as $key => $newValue) {
        // Якщо число — без лапок
        if (is_numeric($newValue)) {
            $replacement = "\$$key = $newValue;";
        } else {
            $escapedValue = str_replace("'", "\\'", $newValue);
            $replacement = "\$$key = '$escapedValue';";
        }

        // Регулярний вираз для заміни відповідного рядка
        $config_content = preg_replace(
            "/\\$$key\s*=\s*(['\"]?)(.*?)\\1\s*;/",
            $replacement,
            $config_content
        );
    }

    file_put_contents($configPath, $config_content);

    // Перезавантажити змінні
    require $configPath;
}


// Завантаження платежів
$payments = json_decode(file_get_contents('../payments/payments.json'), true);

if (!is_array($payments) || count($payments) === 0) {
    echo '<div class="container mt-4"><div class="alert alert-warning text-center">Файл платежів порожній або не містить жодного запису.</div></div>';
    $payments = []; // щоб не було помилок нижче при відображенні
}

$payment_id = $_GET['payment_id'] ?? '';
$order_id = $_GET['order_id'] ?? '';
$sender_phone = $_GET['sender_phone'] ?? '';

if ($payment_id || $order_id || $sender_phone) {
    $payments = array_filter($payments, function ($payment) use ($payment_id, $order_id, $sender_phone) {
        $match = true;
        if ($payment_id !== '') {
            $match = $match && isset($payment['payment_id']) && stripos($payment['payment_id'], $payment_id) !== false;
        }
        if ($order_id !== '') {
            $match = $match && isset($payment['order_id']) && stripos($payment['order_id'], $order_id) !== false;
        }
        if ($sender_phone !== '') {
            $match = $match && isset($payment['sender_phone']) && stripos($payment['sender_phone'], $sender_phone) !== false;
        }
        return $match;
    });
    $payments = array_values($payments); // перенумерація
}



$itemsPerPage = 6;
$totalItems = count($payments);
$totalPages = ceil($totalItems / $itemsPerPage);
$page = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$startIndex = ($page - 1) * $itemsPerPage;
$endIndex = min($startIndex + $itemsPerPage - 1, $totalItems - 1);

// PDF генерація
function win1251($text) {
    return iconv("UTF-8", "windows-1251//IGNORE", $text);
}

function generatePDF($payment) {
    ob_clean();
    $pdf = new FPDF();
    $pdf->AddFont('DejaVu', '', 'DejaVuSans.php');
    $pdf->AddPage();
    $pdf->SetFont('DejaVu', '', 12);

$pdf->MultiCell(0, 10, "Receipt Number: " . (isset($payment['payment_id']) && !empty($payment['payment_id']) ? $payment['payment_id'] : 'system_order_id:'.$payment['order_id']));
    $pdf->MultiCell(0, 10, "Status: " . ucfirst($payment['status']));
    $pdf->MultiCell(0, 10, "Amount: " . $payment['amount'] . " UAH");
    $pdf->MultiCell(0, 10, "Payment method: " . ucfirst($payment['paytype']));
    if (!empty($payment['sender_phone'])) {
        $pdf->MultiCell(0, 10, "Sender's phone: " . $payment['sender_phone']);
    }

    $pdf->Output('I', 'check_' . $payment['order_id'] . '.pdf');
    exit;
}

if (isset($_GET['generate_pdf'], $_GET['order_id'])) {
    foreach ($payments as $payment) {
        if ($payment['order_id'] === $_GET['order_id']) {
            generatePDF($payment);
        }
    }
}




?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Перегляд Платежів</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
            #toggleIcon {
        transition: transform 0.3s ease;
        font-size: 1.2rem;
         }
         .rotated {
               transform: rotate(90deg);
         }
        .payment-row {
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .payment-success { background-color: #d4edda; border-left: 5px solid green; }
        .payment-failure { background-color: #f8d7da; border-left: 5px solid red; }
        .pagination { justify-content: center; }
        .btn-pdf { margin-top: 10px; }
        .form-control[readonly] { background-color: #e9ecef; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="text-center mb-4">Payment List</h1>
<form method="get" class="mb-4">
    <div class="form-row">
        <div class="col-md-3">
            <input type="text" name="payment_id" class="form-control" placeholder="Paid document" value="<?= htmlspecialchars($_GET['payment_id'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="order_id" class="form-control" placeholder="System order_id..." value="<?= htmlspecialchars($_GET['order_id'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="sender_phone" class="form-control" placeholder="Phone number..." value="<?= htmlspecialchars($_GET['sender_phone'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block" type="submit">Search</button>
        </div>
    </div>
</form>

   
    <!-- Налаштування оплати -->
<!-- Налаштування оплати -->
<div class="card mb-4">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center" id="toggleSettings" style="cursor: pointer;">
        <strong>Payment Settings</strong>
        <span id="toggleIcon" class="rotate-arrow">&#9654;</span>
    </div>
    <div class="card-body collapse-settings" id="settingsPanel" style="display: none;">
        <form method="post">
            <input type="hidden" name="update_config" value="1">
            <div class="form-group">
                <label>Public Key</label>
                <input type="text" name="public_key" class="form-control" value="<?= htmlspecialchars($public_key) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Private Key</label>
                <input type="text" name="private_key" class="form-control" value="<?= htmlspecialchars($private_key) ?>" readonly>
            </div>
            <div class="form-group">
                <label>URL успішної оплати</label>
                <input type="text" name="pay_success_url" class="form-control" value="<?= htmlspecialchars($pay_success_url) ?>" readonly>
            </div>
            <div class="form-group">
                <label>Pay amount (грн)</label>
                <input type="number" step="0.01" name="pay_amount" class="form-control" value="<?= htmlspecialchars($pay_amount) ?>" readonly>
            </div>
            <div class="custom-control custom-switch mb-3">
                <input type="checkbox" class="custom-control-input" id="editSwitch">
                <label class="custom-control-label" for="editSwitch">Редагувати</label>
            </div>
            <button type="submit" class="btn btn-success" id="saveBtn" disabled>Зберегти зміни</button>
        </form>
    </div>
</div>


    <!-- Список платежів -->
    <?php for ($i = $startIndex; $i <= $endIndex; $i++): ?>
    <!--Форматуємо дату створення платежу-->
    <?php $millis = $payments[$i]['created_date'];
        $timestamp = intval($millis / 1000);

        // Створюємо DateTime з часовою зоною GMT+3
        $dt = new DateTime("@$timestamp"); // знак @ означає timestamp
        $dt->setTimezone(new DateTimeZone('Europe/Kyiv'));?>
        
        <div class="payment-row <?= ($payments[$i]['status'] === 'success') ? 'payment-success' : 'payment-failure'; ?>">
                <?php if (!empty($payments[$i]['payment_id'])): ?>
        <h5 style="text-align: center;"><strong>Номер платіжного документа: <?= $payments[$i]['payment_id']; ?></strong></h5>
    <?php endif; ?>
            <p ><strong>System order_id: </strong><?= $payments[$i]['order_id']; ?></p>
            <p><strong>Status:</strong> <?= ucfirst($payments[$i]['status']); ?></p>
            <p><strong>Amount:</strong> <?= $payments[$i]['amount']; ?> грн</p>
            <p><strong>Payment method:</strong> <?= ucfirst($payments[$i]['paytype']); ?></p>
            <p><strong>Created date: </strong> <?=$dt->format("Y-m-d H:i:s");?></p>
            <?php if ($payments[$i]['sender_phone']): ?>
                <p><strong>Phone:</strong> <?= $payments[$i]['sender_phone']; ?></p>
            <?php endif; ?>
            <a href="?generate_pdf=1&order_id=<?= $payments[$i]['order_id']; ?>" class="btn btn-info btn-pdf">Згенерувати PDF</a>
        </div>
    <?php endfor; ?>

    <!-- Пагінація -->
    <nav class="pagination mt-4">
        <ul class="pagination">
            <li class="page-item <?= ($page == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?= $page - 1; ?>">Попередня</a>
            </li>
            <li class="page-item <?= ($page == $totalPages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?= $page + 1; ?>">Наступна</a>
            </li>
        </ul>
    </nav>
</div>

<script>
    document.getElementById('editSwitch').addEventListener('change', function () {
        const isEditable = this.checked;
        document.querySelectorAll('input.form-control').forEach(input => input.readOnly = !isEditable);
        document.getElementById('saveBtn').disabled = !isEditable;
    });
</script>
<script>
    // Увімкнення редагування
    document.getElementById('editSwitch').addEventListener('change', function () {
        const editable = this.checked;
        document.querySelectorAll('#settingsPanel input.form-control').forEach(input => input.readOnly = !editable);
        document.getElementById('saveBtn').disabled = !editable;
    });

    // Розгортання / згортання блоку
    document.getElementById('toggleSettings').addEventListener('click', function () {
        const panel = document.getElementById('settingsPanel');
        const icon = document.getElementById('toggleIcon');
        const isOpen = panel.style.display === 'block';

        panel.style.display = isOpen ? 'none' : 'block';
        icon.classList.toggle('rotated', !isOpen);
    });
</script>


</body>
</html>