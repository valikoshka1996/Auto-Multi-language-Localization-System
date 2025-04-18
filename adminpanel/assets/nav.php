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
        margin: 0 !important;
  padding-top: 110px; /* Збільшено відступ згори */
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

    @media (max-width: 768px) {
      .admin-navbar {
        flex-direction: column !important;
        gap: 15px !important;
      }
    }
  </style>
</head>
<body style="margin:0 !important; padding:0 !important;">

  <div class="admin-nav-wrapper">
    <nav class="admin-navbar">
      <a href="admin.php"><i class="fas fa-globe"></i> Localisation</a>
      <a href="media_manager.php"><i class="fas fa-photo-video"></i> Media Manage</a>
      <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="manage.php"><i class="fas fa-key"></i> Change Admin Password</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
  </div>

</body>
</html>
