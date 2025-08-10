<?php
session_start();
require 'config.php'; // Adjust path if needed

// Redirect non-admins
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit;
}

// Fallback for undefined username
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin';

// Count total users
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;

// Count total blogs
$blogCount = $conn->query("SELECT COUNT(*) AS total FROM blog_posts")->fetch_assoc()['total'] ?? 0;

// Count total trainers
$trainerCount = 0;
if ($conn->query("SHOW TABLES LIKE 'trainers'")->num_rows == 1) {
    $trainerCount = $conn->query("SELECT COUNT(*) AS total FROM trainers")->fetch_assoc()['total'] ?? 0;
}

// Count total classes
$classCount = 0;
if ($conn->query("SHOW TABLES LIKE 'classes'")->num_rows == 1) {
    $classCount = $conn->query("SELECT COUNT(*) AS total FROM classes")->fetch_assoc()['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - FitZone</title>

  <link rel="stylesheet" href="./assets/css/dashboard.css" />
</head>
<body>

  <div class="sidebar">
    <h2>FitZone Admin</h2>
    <p>Welcome, <strong><?= $username ?></strong> (Admin)</p>
    <a href="#">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_blog.php">Manage Blogs</a>
    <a href="manage_trainer.php">Manage Trainers</a>
    <a href="manage_classes.php">Manage Classes</a>
    <a href="manage_membership.php">Manage Membership</a>

    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <a href="index.php" class="back-link">&#8592; Back to Homepage</a>
    <div class="header">
      <h1>Dashboard Summary</h1>
    </div>

    <div class="summary">
      <div class="card">
        <h2><?= $userCount ?></h2>
        <p>Total Users</p>
      </div>
      <div class="card">
        <h2><?= $blogCount ?></h2>
        <p>Total Blog Posts</p>
      </div>
      <div class="card">
        <h2><?= $trainerCount ?></h2>
        <p>Total Trainers</p>
      </div>
      <div class="card">
        <h2><?= $classCount ?></h2>
        <p>Total Classes</p>
      </div>
    </div>
  </div>

</body>
</html>
