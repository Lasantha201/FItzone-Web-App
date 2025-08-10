<?php
session_start();
require 'partials/database.php';

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                // Save user info in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];  // make sure DB has username field
                $_SESSION['role'] = $user['role'];

                header("Location: index.php");
                exit;
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'No user found with this email.';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sign In - FitZone</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    /* Your CSS here, unchanged */
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

    .signin {
      padding-top: 50px;
      padding-bottom: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .signin-container {
      max-width: 500px;
      width: 100%;
      background-color: var(--white);
      padding: 40px;
      border-radius: var(--radius-10);
      box-shadow: var(--shadow-1);
    }

    .signin h2 {
      text-align: center;
      font-size: var(--fs-2);
      margin-bottom: 20px;
    }

    .signin-form input {
      width: 100%;
      padding: 14px;
      margin-bottom: 14px;
      border: 1px solid var(--gainsboro);
      border-radius: var(--radius-6);
      font-size: var(--fs-6);
    }

    .signin-form button {
      width: 100%;
      background-color: var(--coquelicot);
      color: var(--white);
      border: none;
      padding: 12px;
      font-size: var(--fs-6);
      font-weight: var(--fw-700);
      border-radius: var(--radius-6);
      cursor: pointer;
    }

    .signin-form button:hover {
      background-color: var(--rich-black-fogra-29-1);
    }

    .forgot-password {
      text-align: left;
      margin-bottom: 20px;
    }

    .forgot-password a {
      font-size: var(--fs-6);
      color: var(--coquelicot);
      text-decoration: none;
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

    .error-message {
      color: red;
      text-align: center;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>
  <section class="signin">
    <div class="signin-container">
      <h2>Sign In to FitZone</h2>

      <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form class="signin-form" action="signin.php" method="POST" novalidate>
        <input type="email" name="email" placeholder="Email Address" required autofocus value="<?= htmlspecialchars($email) ?>" />
        <input type="password" name="password" placeholder="Password" required />

        <div class="forgot-password">
          <a href="forgot_password.php">Forgot Password?</a>
        </div>

        <button type="submit">Sign In</button>
      </form>

      <div class="bottom-text">
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
      </div>
    </div>
  </section>
</body>
</html>
