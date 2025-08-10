<?php
session_start();
require 'config.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$userId = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);

  if (empty($username) || empty($email)) {
    $error = "All fields are required.";
  } else {
    // Update user info
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $userId);
    if ($stmt->execute()) {
      $success = "Profile updated successfully.";
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;
    } else {
      $error = "Something went wrong. Please try again.";
    }
    $stmt->close();
  }
}

// Fetch current user info
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: "Segoe UI", sans-serif;
      background-color: #f9f9f9;
      padding: 40px;
    }

    .container {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    label {
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    input[type="email"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .btn-submit {
      background-color: #ff5722;
      color: white;
      padding: 10px 20px;
      margin-top: 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      width: 100%;
    }

    .btn-submit:hover {
      background-color: #e64a19;
    }

    .msg {
      text-align: center;
      margin-top: 15px;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }

    .btn-back {
      display: block;
      margin-top: 20px;
      text-align: center;
      text-decoration: none;
      color: #333;
    }

    .btn-back:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

 
  <div class="container">
     <a href="dashboard.php" class="btn-back">&larr; Back to Dashboard</a>
    <h2>Edit Profile</h2>

    <?php if ($success): ?>
      <p class="msg success"><?= $success ?></p>
    <?php elseif ($error): ?>
      <p class="msg error"><?= $error ?></p>
    <?php endif; ?>

    <form action="edit_profile.php" method="POST">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

      <button type="submit" class="btn-submit">Update Profile</button>
    </form>

   
  </div>

</body>
</html>
