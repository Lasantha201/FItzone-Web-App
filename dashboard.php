<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS Styling -->
  <style>
    :root {
      --primary: #ff4500;
      --dark: #1a1a1a;
      --light: #ffffff;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }

    body {
      background-color: #f5f5f5;
      padding: 20px;
    }

    .dashboard {
      max-width: 900px;
      margin: auto;
      background-color: var(--light);
      padding: 30px;
      border-radius: 12px;
      box-shadow: var(--shadow);
    }

    h1 {
      color: var(--dark);
      text-align: center;
      margin-bottom: 20px;
    }

    .card {
      background-color: #fff;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 10px;
      margin-top: 30px;
    }

    .actions {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .btn {
      background-color: var(--primary);
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #cc3700;
    }

    @media (max-width: 600px) {
      .dashboard {
        padding: 20px 15px;
      }

      .actions {
        flex-direction: column;
        align-items: center;
      }

      .btn {
        width: 100%;
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <div class="dashboard">
    <a href="index.php" class="btn">&#8592; Back to Home Page</a>
    <h1>Welcome, <?= htmlspecialchars($name) ?> 👋</h1>

    <div class="card">
      <p>You are logged in as a member of FitZone Fitness Center.</p>
      <p>Use the buttons below to access your features.</p>
    </div>

    <div class="actions">
      <a href="membership_status.php" class="btn">My Membership</a>
      <a href="edit_profile.php" class="btn">Edit Profile</a>
      <a href="logout.php" class="btn">Logout</a>
    </div>
  </div>

</body>
</html>
