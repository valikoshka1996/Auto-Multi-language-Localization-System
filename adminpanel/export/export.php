<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$log_file = '../../logs/log.txt';
$logs = [];
if (file_exists($log_file)) {
    $logs = file($log_file, FILE_IGNORE_NEW_LINES);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export Logs as PDF</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #777;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        #download-btn {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Access Logs PDF Export</h1>
    <button id="download-btn">⬇️ Download PDF</button>
    
    <div id="pdf-content">
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>IP Address</th>
                    <th>Country</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
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
    </div>

    <script>
        document.getElementById("download-btn").addEventListener("click", function () {
            const element = document.getElementById("pdf-content");
            const opt = {
                margin:       0.5,
                filename:     'access_logs.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(element).set(opt).save();
        });
    </script>
</body>
</html>
