<?php
require 'config.php'; // Make sure this has your DB connection

$plan = $_GET['plan'] ?? '';
$name = $email = $phone = $message = '';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $plan = $_POST['plan'];
  $message = trim($_POST['message']);

  if ($name && $email && $phone && $plan) {
    $stmt = $conn->prepare("INSERT INTO memberships (name, email, phone, plan, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $plan, $message);

    if ($stmt->execute()) {
      $success = "Your membership request has been submitted.";
      $name = $email = $phone = $message = '';
    } else {
      $error = "Something went wrong. Try again later.";
    }
    $stmt->close();
  } else {
    $error = "Please fill in all required fields.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Membership Form</title>
  <link rel="stylesheet" href="styles/dashboard.css"> 
  <style>
    body {
      font-family: sans-serif;
      background-color: #f4f4f4;
      padding: 40px;
    }
    .form-container {
      background: white;
      max-width: 500px;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 { text-align: center; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; }
    .form-group input,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .btn {
      display: inline-block;
      background: #ff5722;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .btn:hover { background: #e64a19; }
    .message { margin: 15px 0; text-align: center; }
    .back-link { display: block; margin-bottom: 20px; text-align: center; text-decoration: none; color: #333; }
  </style>
</head>
<body>
  <a href="pricing.php" class="back-link">&larr; Back to Pricing</a>

  <div class="form-container">
    <h2>Join FitZone - <?= htmlspecialchars($plan) ?> Plan</h2>

    <?php if ($success): ?>
      <p class="message" style="color: green;"> <?= $success ?> </p>
    <?php elseif ($error): ?>
      <p class="message" style="color: red;"> <?= $error ?> </p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="hidden" name="plan" value="<?= htmlspecialchars($plan) ?>">

      <div class="form-group">
        <label>Name *</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required>
      </div>

      <div class="form-group">
        <label>Email *</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
      </div>

      <div class="form-group">
        <label>Phone *</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
      </div>

      <div class="form-group">
        <label>Message (Optional)</label>
        <textarea name="message" rows="4"><?= htmlspecialchars($message) ?></textarea>
      </div>

      <button type="submit" class="btn">Submit</button>
    </form>
  </div>
</body>
</html>
