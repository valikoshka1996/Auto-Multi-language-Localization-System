<?php
session_start();
include_once '../config.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_password_hash'])) {
    header("Location: index.php");
    exit;
}

$imagesDir = realpath(__DIR__ . '/../images');
$dataFile = __DIR__ . '/media_data.json';
$mediaData = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

$imageFiles = array_values(array_filter(scandir($imagesDir), function ($file) use ($imagesDir) {
    return is_file($imagesDir . DIRECTORY_SEPARATOR . $file) && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
}));

// Обробка POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Видалення
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $filename = $_POST['filename'];
        $path = $imagesDir . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($path)) {
            unlink($path);
            unset($mediaData[$filename]);
            file_put_contents($dataFile, json_encode($mediaData, JSON_PRETTY_PRINT));
        }
        header("Location: media_manager.php");
        exit;
    }

    // Перейменування / опис
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $oldName = $_POST['filename'];
        $newName = $_POST['newname'] ?? $oldName;
        $description = $_POST['description'] ?? '';
        if ($newName !== $oldName) {
            $oldPath = $imagesDir . DIRECTORY_SEPARATOR . $oldName;
            $newPath = $imagesDir . DIRECTORY_SEPARATOR . $newName;
            if (!file_exists($newPath)) {
                rename($oldPath, $newPath);
                unset($mediaData[$oldName]);
                $oldName = $newName;
            }
        }
        $mediaData[$oldName] = $description;
        file_put_contents($dataFile, json_encode($mediaData, JSON_PRETTY_PRINT));
        header("Location: media_manager.php");
        exit;
    }

    // Додавання або заміна
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] === 0) {
        $isReplace = !empty($_POST['replace_existing']);
        $targetName = $isReplace ? $_POST['replace_existing'] : ($_POST['upload_name'] ?: $_FILES['new_image']['name']);
        $targetPath = $imagesDir . DIRECTORY_SEPARATOR . $targetName;
        move_uploaded_file($_FILES['new_image']['tmp_name'], $targetPath);

        if (!empty($_POST['upload_description'])) {
            $mediaData[$targetName] = $_POST['upload_description'];
            file_put_contents($dataFile, json_encode($mediaData, JSON_PRETTY_PRINT));
        }
        header("Location: media_manager.php");
        exit;
    }
}

// Пагінація
$perPage = 6;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPages = ceil(count($imageFiles) / $perPage);
$imagesToShow = array_slice($imageFiles, ($page - 1) * $perPage, $perPage);

include 'assets/nav.php'; // якщо треба
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Медіаменеджер</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
    <h2 class="mb-4">Media managment</h2>

    <!-- Завантажити / Замінити -->
    <form method="POST" enctype="multipart/form-data" class="mb-4 border p-3 bg-white shadow-sm rounded">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="file" name="new_image" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="upload_name" class="form-control" placeholder="Нове ім'я (необов'язково)">
            </div>
            <div class="col-md-3">
                <select name="replace_existing" class="form-select">
                    <option value="">-- Change pic (optional) --</option>
                    <?php foreach ($imageFiles as $file): ?>
                        <option value="<?= htmlspecialchars($file) ?>"><?= htmlspecialchars($file) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" name="upload_description" class="form-control" placeholder="Опис">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success w-100">Save</button>
            </div>
        </div>
    </form>

<div class="row g-3">
    <?php foreach ($imagesToShow as $file): ?>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="../images/<?= urlencode($file) ?>" class="card-img-top" style="object-fit: cover; height: 200px; cursor: pointer;"
                     data-bs-toggle="modal" data-bs-target="#modalImage" data-bs-image="../images/<?= urlencode($file) ?>">
                <div class="card-body">
                    <form method="POST" class="d-grid gap-2 mb-2">
                        <input type="hidden" name="filename" value="<?= htmlspecialchars($file) ?>">
                        <input type="text" name="newname" class="form-control" value="<?= htmlspecialchars($file) ?>">
                        <textarea name="description" class="form-control" rows="2" placeholder="Опис"><?= htmlspecialchars($mediaData[$file] ?? '') ?></textarea>
                        <button type="submit" name="action" value="update" class="btn btn-primary btn-sm">Update</button>
                    </form>

                    <form method="POST" onsubmit="return confirm('Видалити це фото?')">
                        <input type="hidden" name="filename" value="<?= htmlspecialchars($file) ?>">
                        <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm w-100">Delete</button>
                    </form>

                    <form method="POST" enctype="multipart/form-data" class="mt-2">
                        <input type="hidden" name="replace_existing" value="<?= htmlspecialchars($file) ?>">
                        <div class="input-group input-group-sm">
                            <input type="file" name="new_image" class="form-control form-control-sm" required>
                            <button type="submit" class="btn btn-warning btn-sm">Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    <!-- Пагінація -->
    <?php if ($totalPages > 1): ?>
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="modalImage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-dark">
            <img id="modalFullImage" src="" class="img-fluid" style="width: 100%; height: auto;">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalImage = document.getElementById('modalFullImage');
    const modal = document.getElementById('modalImage');
    modal.addEventListener('show.bs.modal', function (event) {
        const img = event.relatedTarget;
        modalImage.src = img.getAttribute('data-bs-image');
    });
</script>
</body>
</html>
