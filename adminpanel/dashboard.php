<?php
include 'assets/nav.php';
session_start();

// Перевірка, чи авторизований користувач
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php"); // Перенаправлення на сторінку логіну
    exit;
}

// Ваш код для парсингу логів і відображення графіків...

// Парсимо логи
$log_file = '../logs/log.txt';
$logs = [];
if (file_exists($log_file)) {
    $logs = file($log_file, FILE_IGNORE_NEW_LINES);
}

// Аналізуємо дані для графіків (для всіх логів)
$country_count = [];
foreach ($logs as $log) {
    preg_match('/Language: (\w{2})/', $log, $matches);
    if (isset($matches[1])) {
        $country_code = $matches[1];
        if (!isset($country_count[$country_code])) {
            $country_count[$country_code] = 0;
        }
        $country_count[$country_code]++;
    }
}

// Додаємо пагінацію
$logs_per_page = 20; // кількість записів на сторінці
$total_logs = count($logs);
$total_pages = ceil($total_logs / $logs_per_page);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $total_pages)); // Обмежуємо сторінки від 1 до максимального
$start = ($page - 1) * $logs_per_page;
$logs_to_show = array_slice($logs, $start, $logs_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Logs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Access Logs Dashboard</h1>
            <p>View and analyze access logs to track visits to your website.</p>
        </header>

        <!-- Графік по країнах -->
        <div class="chart-container">
            <canvas id="countryChart"></canvas>
        </div>

        <!-- Таблиця логів -->
        <table class="log-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>IP Address</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs_to_show as $log): ?>
                    <?php
                    preg_match('/\[(.*?)\]/', $log, $timestamp);
                    preg_match('/IP: ([\d\.]+)/', $log, $ip);
                    preg_match('/Language: (\w{2})/', $log, $country);
                    ?>
                    <tr>
                        <td><?= $timestamp[1] ?? 'Unknown' ?></td>
                        <td><?= $ip[1] ?? 'Unknown' ?></td>
                        <td><?= $country[1] ?? 'Unknown' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Пагінація -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=1" class="first"><i class="fas fa-angle-double-left"></i> First</a>
                <a href="?page=<?= $page - 1 ?>" class="prev"><i class="fas fa-arrow-left"></i> Previous</a>
            <?php endif; ?>

            <span>Page <?= $page ?> of <?= $total_pages ?></span>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="next">Next <i class="fas fa-arrow-right"></i></a>
                <a href="?page=<?= $total_pages ?>" class="last">Last <i class="fas fa-angle-double-right"></i></a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Графік для відображення кількості відвідувань по країнах
        const countryCount = <?= json_encode($country_count) ?>;
        const countryLabels = Object.keys(countryCount);
        const countryValues = Object.values(countryCount);

        const ctx = document.getElementById('countryChart').getContext('2d');
        const countryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: countryLabels,
                datasets: [{
                    label: 'Visits by Country',
                    data: countryValues,
                    backgroundColor: '#4e73df',
                    borderColor: '#4e73df',
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
// Вихід з системи
if (isset($_GET['logout'])) {
    session_start();
    session_destroy();
    header("Location: login.php"); // Перенаправлення на сторінку логіну
    exit;
}
?>
