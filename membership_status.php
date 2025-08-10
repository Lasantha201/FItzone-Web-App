<?php
session_start();
require 'config.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$userEmail = $_SESSION['email'] ?? '';
$membership = null;

if ($userEmail) {
  $stmt = $conn->prepare("SELECT * FROM memberships WHERE email = ? ORDER BY registered_on DESC LIMIT 1");
  $stmt->bind_param("s", $userEmail);
  $stmt->execute();
  $result = $stmt->get_result();
  $membership = $result->fetch_assoc();
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Membership Status</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background-color: #f4f4f4;
      padding: 40px;
    }
    .container {
      max-width: 700px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .info {
      margin-bottom: 20px;
      line-height: 1.7;
    }
    .info strong {
      display: inline-block;
      width: 150px;
    }
    .status {
      font-weight: bold;
      padding: 8px 15px;
      border-radius: 5px;
      display: inline-block;
    }
    .paid {
      color: green;
      background-color: #d4edda;
    }
    .pending {
      color: #b36b00;
      background-color: #fff3cd;
    }
    .btn-back {
      display: block;
      text-align: center;
      margin-top: 30px;
      background-color: #ff5722;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
    }
    .btn-back:hover {
      background-color: #e64a19;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>My Membership Status</h2>

    <?php if ($membership): ?>
      <?php if (isset($membership['status']) && $membership['status'] === 'paid'): ?>
        <div class="info"><strong>Name:</strong> <?= htmlspecialchars($membership['name']) ?></div>
        <div class="info"><strong>Email:</strong> <?= htmlspecialchars($membership['email']) ?></div>
        <div class="info"><strong>Phone:</strong> <?= htmlspecialchars($membership['phone']) ?></div>
        <div class="info"><strong>Plan:</strong> <?= htmlspecialchars($membership['plan']) ?></div>
        <div class="info"><strong>Message:</strong> <?= htmlspecialchars($membership['message']) ?></div>
        <div class="info"><strong>Registered On:</strong> <?= htmlspecialchars($membership['registered_on']) ?></div>
        <div class="info">
          <strong>Status:</strong>
          <span class="status paid">Paid</span>
        </div>
      <?php else: ?>
        <p style="text-align: center;">⏳ Your membership is still pending approval by the admin.</p>
      <?php endif; ?>
    <?php else: ?>
      <p style="text-align: center;">You have not submitted a membership request yet.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="btn-back">&larr; Back to Dashboard</a>
  </div>

</body>
</html>
