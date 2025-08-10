<?php
session_start();  // Start session if you plan to check login later
require 'config.php';  // Include DB connection
require 'partials/header.php';


// Fetch classes from DB
$sql = "SELECT * FROM classes ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!-- Classes Page Custom Styles -->
<style>
  .header {
    background-color: var(--rich-black-fogra-29-1) !important;
  }
  .navbar-link {
    color: var(--white) !important;
  }
  .navbar-link:is(:hover, :focus, .active) {
    color: var(--coquelicot) !important;
  }
  .logo {
    color: var(--white) !important;
  }
  section.class {
    padding-top: 200px;
  }
  .coach-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 20px;
  }
  .coach-info img {
    width: 30px;
    height: 30px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid var(--coquelicot);
  }
  .coach-info span {
    color: var(--rich-black-fogra-29-1);
    font-weight: var(--fw-700);
    font-size: var(--fs-6);
  }
  /* Minimize image size inside cards */
  .class-card .card-banner img {
    height: 180px;
    object-fit: cover;
    width: 100%;
    border-radius: var(--radius-10);
  }
  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
  }
  .progress-wrapper {
    display: flex;
    justify-content: space-between;
    font-weight: var(--fw-700);
    margin-top: 12px;
  }
  .progress-bg {
    background-color: var(--gainsboro);
    border-radius: var(--radius-6);
    height: 10px;
    margin-top: 6px;
  }
  .progress-bar {
    background-color: var(--coquelicot);
    height: 100%;
    border-radius: var(--radius-6);
  }
</style>

<!-- Classes Section -->
<section class="class section" id="classes">
  <div class="container">

    <p class="section-subtitle">Our Programs</p>
    <h2 class="h2 section-title">Explore FitZone Classes</h2>

    <div class="grid">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($class = $result->fetch_assoc()): ?>
          <div class="class-card">
            <div class="card-banner">
              <img src="assets/images/<?php echo htmlspecialchars($class['image']); ?>" alt="<?php echo htmlspecialchars($class['title']); ?>" class="w-100">
            </div>
            <div class="card-content">
              <div class="title-wrapper">
                <div class="title-icon">
                  <!-- Example icons for classes, you can adjust this to be dynamic if needed -->
                  <?php
                    // Map titles to Ionicons icon names (optional)
                    $icons = [
                      'Cardio Blast' => 'flame-outline',
                      'Strength Training' => 'barbell-outline',
                      'Yoga & Flexibility' => 'leaf-outline',
                      'Wellness & Recovery' => 'heart-outline'
                    ];
                    $iconName = $icons[$class['title']] ?? 'fitness-outline';
                  ?>
                  <ion-icon name="<?php echo $iconName; ?>"></ion-icon>
                </div>
                <h3 class="h3 card-title"><?php echo htmlspecialchars($class['title']); ?></h3>
              </div>
              <p class="card-text">
                <?php echo htmlspecialchars($class['description']); ?>
              </p>
              <div class="progress-wrapper">
                <span>Intensity</span>
                <span><?php echo (int)$class['intensity']; ?>%</span>
              </div>
              <div class="progress-bg">
                <div class="progress-bar" style="width: <?php echo (int)$class['intensity']; ?>%"></div>
              </div>
              <div class="coach-info">
                <img src="assets/images/<?php echo htmlspecialchars($class['coach_image']); ?>" alt="<?php echo htmlspecialchars($class['coach_name']); ?>">
                <span><?php echo htmlspecialchars($class['coach_name']); ?></span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No classes available at the moment.</p>
      <?php endif; ?>
    </div> <!-- /.grid -->

  </div>
</section>

<?php
$conn->close();
require 'partials/footer.php';
?>
