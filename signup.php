<?php
session_start();
require 'partials/database.php';

$error = '';
$success = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$username || !$email || !$password || !$confirm_password) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        // Check if email or username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Email or username already registered.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $username, $email, $password_hash);

            if ($stmt->execute()) {
                $success = 'Registration successful! You can now <a href="signin.php">sign in</a>.';
                // Clear form fields after success
                $username = $email = '';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign Up - FitZone</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    /* Use your existing CSS for styling, same as signin page */
    :root {
      --white: #ffffff;
      --gainsboro: #dcdcdc;
      --rich-black-fogra-29-1: #0b0c10;
      --coquelicot: #ff4c29;
      --fs-2: 2rem;
      --fs-6: 1rem;
      --fw-700: 700;
      --radius-6: 6px;
      --radius-10: 10px;
      --shadow-1: 0 4px 20px rgba(0, 0, 0, 0.1);
      --transition-1: 0.3s ease;
      --ff-catamaran: 'Catamaran', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background-color: var(--white);
      font-family: var(--ff-catamaran);
    }

    section.signup {
      padding-top: 50px;
      padding-bottom: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .signup-container {
      max-width: 500px;
      width: 100%;
      background-color: var(--white);
      padding: 40px;
      border-radius: var(--radius-10);
      box-shadow: var(--shadow-1);
    }

    .signup h2 {
      text-align: center;
      font-size: var(--fs-2);
      margin-bottom: 20px;
    }

    .signup-form input {
      width: 100%;
      padding: 14px;
      margin-bottom: 14px;
      border: 1px solid var(--gainsboro);
      border-radius: var(--radius-6);
      font-size: var(--fs-6);
    }

    .signup-form button {
      width: 100%;
      background-color: var(--coquelicot);
      color: var(--white);
      border: none;
      padding: 12px;
      font-size: var(--fs-6);
      font-weight: var(--fw-700);
      border-radius: var(--radius-6);
      cursor: pointer;
      transition: var(--transition-1);
    }

    .signup-form button:hover {
      background-color: var(--rich-black-fogra-29-1);
    }

    .message {
      text-align: center;
      margin-bottom: 16px;
    }

    .message.error {
      color: red;
    }

    .message.success {
      color: green;
    }

    .bottom-text {
      text-align: center;
      margin-top: 16px;
      font-size: var(--fs-6);
    }

    .bottom-text a {
      color: var(--coquelicot);
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <section class="signup">
    <div class="signup-container">
      <h2>Create Your FitZone Account</h2>

      <?php if ($error): ?>
        <p class="message error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <?php if ($success): ?>
        <p class="message success"><?= $success ?></p>
      <?php endif; ?>

      <form class="signup-form" action="signup.php" method="POST" novalidate>
        <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($username) ?>" />
        <input type="email" name="email" placeholder="Email Address" required value="<?= htmlspecialchars($email) ?>" />
        <input type="password" name="password" placeholder="Password" required minlength="6" />
        <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6" />

        <button type="submit">Sign Up</button>
      </form>

      <div class="bottom-text">
        <p>Already have an account? <a href="signin.php">Sign In</a></p>
      </div>
    </div>
  </section>
</body>
</html>
