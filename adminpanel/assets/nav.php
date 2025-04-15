<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel Menu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
    }

    .admin-navbar {
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      padding: 0.75rem 2rem;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .admin-navbar a {
      text-decoration: none;
      color: #333;
      font-weight: 500;
      font-size: 16px;
      display: flex;
      align-items: center;
      transition: 0.3s ease;
    }

    .admin-navbar a i {
      margin-right: 8px;
    }

    .admin-navbar a:hover {
      color: #007bff;
      transform: translateY(-2px);
    }

    @media (max-width: 768px) {
      .admin-navbar {
        flex-direction: column;
        gap: 15px;
      }
    }
  </style>
</head>
<body>

  <nav class="admin-navbar">
    <a href="admin.php"><i class="fas fa-globe"></i> Localisation</a>
    <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="manage.php"><i class="fas fa-key"></i> Change Admin Password</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </nav>

  <!-- Контент нижче -->
  <div class="container mt-5">
    <h2>👋 Welcome to Admin Panel</h2>
    <!-- Тут твій контент -->
  </div>

</body>
</html>
