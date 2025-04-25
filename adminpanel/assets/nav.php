<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel Menu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    .admin-nav-wrapper {
      margin: 0 !important;
      padding: 0 !important;
      box-sizing: border-box !important;
    }

    .admin-navbar {
      background-color: #ffffff !important;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05) !important;
      padding: 0.75rem 2rem !important;
      display: flex !important;
      justify-content: center !important;
      align-items: center !important;
      gap: 30px !important;
      flex-wrap: wrap !important;
      position: relative !important;
      top: 0 !important;
      z-index: 9999 !important;
      margin: 0 !important;
      padding-top: 110px; /* Increased top padding */
      background-color: #f4f6f9;
    }

    .admin-navbar a {
      text-decoration: none !important;
      color: #333 !important;
      font-weight: 500 !important;
      font-size: 16px !important;
      display: flex !important;
      align-items: center !important;
      transition: 0.3s ease !important;
    }

    .admin-navbar a i {
      margin-right: 8px !important;
    }

    .admin-navbar a:hover {
      color: #007bff !important;
      transform: translateY(-2px) !important;
    }

    .navbar-toggler {
      border: none !important;
    }

    .navbar-collapse {
      justify-content: center !important;
    }

    .admin-navbar .dropdown-menu {
      min-width: 200px;
    }

@media (max-width: 768px) {
  .admin-navbar {
    flex-direction: column !important;
    gap: 15px !important;
  }

  .admin-navbar a {
    font-size: 14px !important;
  }

  /* ❌ Закоментуй або видали ось це: */
  /* .navbar-collapse {
    display: none;
  } */

  .navbar-toggler {
    display: block !important;
  }
}
  </style>
</head>
<body style="margin:0 !important; padding:0 !important;">

  <div class="admin-nav-wrapper">
    <nav class="admin-navbar navbar navbar-expand-lg navbar-light">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="admin.php" class="nav-link"><i class="fas fa-globe"></i> Localization</a>
          </li>
          <li class="nav-item">
            <a href="media_manager.php" class="nav-link"><i class="fas fa-photo-video"></i> Media Manage</a>
          </li>
          <li class="nav-item">
            <a href="dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="manage.php" class="nav-link"><i class="fas fa-key"></i> Change Admin Password</a>
          </li>
          <li class="nav-item">
            <a href="payment.php" class="nav-link"><i class="fas fa-credit-card"></i> Payments</a>
          </li>
         <li class="nav-item">
            <a href="general.php" class="nav-link"><i class="fas fa-gear"></i> General</a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
