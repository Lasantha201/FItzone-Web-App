<?php
require 'partials/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);

  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    // Email exists, redirect to change password
    header("Location: reset-password.php?email=" . urlencode($email));
    exit();
  } else {
    // Email not found
    $error = "Invalid email address.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | FitZone</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .box {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 400px;
      text-align: center;
    }

    .box h2 {
      margin-bottom: 20px;
      color: #333;
    }

    .box input {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .box button {
      width: 100%;
      padding: 12px;
      background: #ff3d00;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .box .error {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Reset Your Password</h2>
    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Enter your registered email" required>
      <button type="submit">Continue</button>
    </form>
  </div>
</body>
</html>
