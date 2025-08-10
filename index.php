<?php
session_start();
require 'partials/header.php';
require 'config.php';

// Fetch latest 3 blog posts
$sql = "SELECT * FROM blog_posts ORDER BY posted_on DESC LIMIT 3";
$result = $conn->query($sql);

// Fetch 4 classes from DB for homepage
$sqlClasses = "SELECT * FROM classes ORDER BY id ASC LIMIT 4";
$resultClasses = $conn->query($sqlClasses);
?>

<main>
  <article>

    <!-- ////// Hero ///// -->
    <section class="section hero bg-dark has-after has-bg-image" id="home" aria-label="hero" data-section
      style="background-image: url('./assets/images/hero-bg.png')">
      <div class="container">

        <div class="hero-content">

          <p class="hero-subtitle">
            <strong class="strong">Train Hard</strong>Stay Strong
          </p>

          <h1 class="h1 hero-title">Build Strength, Build Life</h1>

          <p class="section-text">
            Unlock your potential through disciplined training, expert guidance, and a community that supports your every step.
          </p>

          <a href="signup.php" class="btn btn-primary">Get Started</a>

        </div>

        <div class="hero-banner">
          <img src="./assets/images/hero-banner.png" width="660" height="753" alt="hero banner" class="w-100">
          <img src="./assets/images/hero-circle-one.png" width="666" height="666" aria-hidden="true" alt=""
            class="circle circle-1">
          <img src="./assets/images/hero-circle-two.png" width="666" height="666" aria-hidden="true" alt=""
            class="circle circle-2">
          <img src="./assets/images/heart-rate.svg" width="255" height="270" alt="heart rate"
            class="abs-img abs-img-1">
          <img src="./assets/images/calories.svg" width="348" height="224" alt="calories" class="abs-img abs-img-2">
        </div>

      </div>
    </section>

    <!-- ////// About ///// -->
    <section class="section about" id="about" aria-label="about">
      <div class="container">

        <div class="about-banner has-after">
          <img src="./assets/images/about-banner1.png" width="660" height="648" loading="lazy" alt="about banner"
            class="w-100">
          <img src="./assets/images/about-circle-one.png" width="660" height="534" loading="lazy" aria-hidden="true"
            alt="" class="circle circle-1">
          <img src="./assets/images/about-circle-two.png" width="660" height="534" loading="lazy" aria-hidden="true"
            alt="" class="circle circle-2">
          <img src="./assets/images/fitness.png" width="650" height="154" loading="lazy" alt="fitness"
            class="abs-img w-100">
        </div>

        <div class="about-content">
          <p class="section-subtitle">About Us</p>
          <h2 class="h2 section-title">Welcome To Our FitZone Gym</h2>

          <p class="section-text">
            FitZone Fitness Center helps you build strength and live healthier. We offer modern equipment, expert trainers, and classes like cardio, yoga, and strength training.
          </p>

          <p class="section-text">
            Open 24/7 in Kurunegala, FitZone is here to support your fitness goals with flexible plans and a friendly atmosphere.
          </p>

          <div class="wrapper">
            <a href="about.php" class="btn btn-primary">Explore More</a>
          </div>
        </div>

      </div>
    </section>

    <!-- ////// Classes (Dynamic from DB) ///// -->
    <section class="section class bg-dark has-bg-image" id="class" aria-label="class"
      style="background-image: url('./assets/images/classes-bg.png')">
      <div class="container">

        <p class="section-subtitle">Our Classes</p>
        <h2 class="h2 section-title text-center">Fitness Classes For Every Goal</h2>

        <ul class="class-list has-scrollbar">
          <?php if ($resultClasses && $resultClasses->num_rows > 0): ?>
            <?php while ($class = $resultClasses->fetch_assoc()): ?>
              <li class="scrollbar-item">
                <div class="class-card">
                  <figure class="card-banner img-holder" style="--width: 416; --height: 240;">
                    <img
                      src="assets/images/<?php echo htmlspecialchars($class['image']); ?>"
                      width="416" height="240"
                      loading="lazy"
                      alt="<?php echo htmlspecialchars($class['title']); ?>"
                      class="img-cover">
                  </figure>

                  <div class="card-content">
                    <div class="title-wrapper">
                      <ion-icon name="fitness-outline" style="color:white;font-size:32px;"></ion-icon>
                      <h3 class="h3">
                        <a href="classes.php" class="card-title">
                          <?php echo htmlspecialchars($class['title']); ?>
                        </a>
                      </h3>
                    </div>

                    <p class="card-text">
                      <?php echo htmlspecialchars($class['description']); ?>
                    </p>

                    <div class="card-progress">
                      <div class="progress-wrapper">
                        <p class="progress-label">Intensity</p>
                        <span class="progress-value"><?php echo (int)$class['intensity']; ?>%</span>
                      </div>

                      <div class="progress-bg">
                        <div class="progress-bar" style="width: <?php echo (int)$class['intensity']; ?>%"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            <?php endwhile; ?>
          <?php else: ?>
            <li>No classes available.</li>
          <?php endif; ?>
        </ul>

      </div>
    </section>

    <!-- ////// Blog Section - Dynamic from DB ///// -->
    <section class="section blog" id="blog" aria-label="blog">
      <div class="container">

        <p class="section-subtitle">Our News</p>
        <h2 class="h2 section-title text-center">Latest Blog Feed</h2>

        <ul class="blog-list has-scrollbar">
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <li class="scrollbar-item">
                <div class="blog-card">
                  <div class="card-banner img-holder" style="--width: 440; --height: 270;">
                    <img
                      src="assets/images/<?= htmlspecialchars($row['image']) ?>"
                      width="440"
                      height="270"
                      loading="lazy"
                      alt="<?= htmlspecialchars($row['title']) ?>"
                      class="img-cover"
                    >
                    <time class="card-meta" datetime="<?= date('Y-m-d', strtotime($row['posted_on'])) ?>">
                      <?= date('j F Y', strtotime($row['posted_on'])) ?>
                    </time>
                  </div>

                  <div class="card-content">
                    <h3 class="h3">
                      <a href="blog.php" class="card-title"><?= htmlspecialchars($row['title']) ?></a>
                    </h3>

                    <p class="card-text">
                      <?= htmlspecialchars(substr($row['content'], 0, 100)) ?>...
                    </p>

                    <a href="blog.php" class="btn-link has-before">Read More</a>
                  </div>
                </div>
              </li>
            <?php endwhile; ?>
          <?php else: ?>
            <li>No blog posts available.</li>
          <?php endif; ?>
        </ul>

      </div>
    </section>

  </article>
</main>

<?php
$conn->close();
require 'partials/footer.php';
?>
