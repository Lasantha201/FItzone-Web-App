<?php
require 'partials/database.php';

$error = '';
$success = '';

if (!isset($_GET['email'])) {
  $error = 'No email provided.';
} else {
  $email = trim($_GET['email']);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);

    if (strlen($password) < 6) {
      $error = 'Password must be at least 6 characters.';
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
      $stmt->bind_param("ss", $hashed, $email);

      if ($stmt->execute()) {
        $success = "Password changed successfully.";
      } else {
        $error = "Failed to update password.";
      }

      $stmt->close();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Change Password</title>
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

    .box .success {
      color: green;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <div class="box">
    <h2>Change Your Password</h2>

    <?php if ($error): ?>
      <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
      <p class="success"><?= htmlspecialchars($success) ?></p>
      <a href="signin.php">Return to login</a>
    <?php else: ?>
      <form method="POST">
        <input type="password" name="password" placeholder="New password" required>
        <button type="submit">Update Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
