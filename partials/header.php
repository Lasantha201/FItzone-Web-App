<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FitZone - Where Strength Meets Dedication</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="./assets/css/style.css" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />

  <!-- Preload Images -->
  <link rel="preload" as="image" href="./assets/images/hero-banner.png" />
  <link rel="preload" as="image" href="./assets/images/hero-circle-one.png" />
  <link rel="preload" as="image" href="./assets/images/hero-circle-two.png" />
  <link rel="preload" as="image" href="./assets/images/heart-rate.svg" />
  <link rel="preload" as="image" href="./assets/images/calories.svg" />

  <!-- Ionicons (for icons used in header) -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body id="top">

  <!-- Header -->
  <header class="header" data-header>
    <div class="container">

      <a href="index.php" class="logo">
        <ion-icon name="barbell-sharp" aria-hidden="true"></ion-icon>
        <span class="span">FitZone</span>
      </a>

      <!-- Navigation -->
      <nav class="navbar" data-navbar>
        <button class="nav-close-btn" aria-label="close menu" data-nav-toggler>
          <ion-icon name="close-sharp" aria-hidden="true"></ion-icon>
        </button>

        <ul class="navbar-list">
          <li><a href="index.php#home" class="navbar-link" data-nav-link>Home</a></li>
          <li><a href="index.php#about" class="navbar-link" data-nav-link>About Us</a></li>
          <li><a href="classes.php" class="navbar-link" data-nav-link>Classes</a></li>
          <li><a href="blog.php" class="navbar-link" data-nav-link>Blog</a></li>
          <li><a href="trainers.php" class="navbar-link" data-nav-link>Trainers</a></li>
          <li><a href="pricing.php" class="navbar-link" data-nav-link>Pricing</a></li>
          <li><a href="contact.php" class="navbar-link" data-nav-link>Contact Us</a></li>

          <?php if (isset($_SESSION['user_name'])): ?>
            <li>
              <a href="#" class="navbar-link" style="color: var(--coquelicot); cursor: default;">
                Hi, <?= htmlspecialchars($_SESSION['user_name']) ?>
              </a>
            </li>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
              <li><a href="admin_dashboard.php" class="navbar-link">Dashboard</a></li>
            <?php else: ?>
              <li><a href="dashboard.php" class="navbar-link">Dashboard</a></li>
            <?php endif; ?>

            <li><a href="logout.php" class="navbar-link">Logout</a></li>
          <?php endif; ?>
        </ul>
      </nav>

      <?php if (!isset($_SESSION['user_name'])): ?>
        <a href="signin.php" class="btn btn-secondary">Join Now</a>
      <?php endif; ?>

      <button class="nav-open-btn" aria-label="open menu" data-nav-toggler>
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
      </button>

    </div>
  </header>
