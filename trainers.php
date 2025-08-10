<?php 
require 'partials/header.php'; 
require 'config.php'; // Your DB connection
?>

<!-- Trainers Page Custom Styles -->
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

  section.trainers {
    padding-top: 200px;
  }

  .trainer-card {
    background-color: var(--white);
    border-radius: var(--radius-10);
    box-shadow: var(--shadow-1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: box-shadow 0.3s ease;
  }

  .trainer-card:hover {
    box-shadow: var(--shadow-2);
  }

  .trainer-photo {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: var(--radius-10) var(--radius-10) 0 0;
  }

  .trainer-content {
    padding: 24px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }

  .trainer-name {
    font-size: var(--fs-4);
    font-weight: var(--fw-900);
    color: var(--rich-black-fogra-29-1);
    margin-bottom: 6px;
    font-family: var(--ff-catamaran);
  }

  .trainer-specialty {
    font-size: var(--fs-6);
    font-weight: var(--fw-700);
    color: var(--coquelicot);
    margin-bottom: 16px;
    text-transform: uppercase;
  }

  .trainer-bio {
    font-size: 1.5rem;
    color: var(--sonic-silver);
    line-height: 1.5;
    flex-grow: 1;
  }

  .trainer-contact {
    margin-top: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .trainer-contact img {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid var(--coquelicot);
  }

  .trainer-contact span {
    font-weight: var(--fw-700);
    color: var(--rich-black-fogra-29-1);
    font-size: var(--fs-6);
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
  }
</style>

<!-- Trainers Section -->
<section class="trainers section" id="trainers">
  <div class="container">

    <center><p class="section-subtitle">Meet The Team</p></center>
    <center><h2 class="h2 section-title">Our Expert Trainers</h2></center>

    <div class="grid">

      <?php 
      $sql = "SELECT * FROM trainers";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0):
        while ($trainer = $result->fetch_assoc()):
      ?>
        <article class="trainer-card">
          <img src="assets/images/<?php echo htmlspecialchars($trainer['photo']); ?>" alt="Trainer <?php echo htmlspecialchars($trainer['name']); ?>" class="trainer-photo" />
          <div class="trainer-content">
            <h3 class="trainer-name"><?php echo htmlspecialchars($trainer['name']); ?></h3>
            <p class="trainer-specialty"><?php echo htmlspecialchars($trainer['specialty']); ?></p>
            <p class="trainer-bio"><?php echo htmlspecialchars($trainer['bio']); ?></p>
            <div class="trainer-contact">
              <img src="assets/images/<?php echo htmlspecialchars($trainer['photo']); ?>" alt="<?php echo htmlspecialchars($trainer['name']); ?>'s photo" />
              <span><?php echo htmlspecialchars($trainer['name']); ?></span>
            </div>
          </div>
        </article>
      <?php
        endwhile;
      else:
      ?>
        <p>No trainers found.</p>
      <?php endif; ?>

    </div>

  </div>
</section>

<?php require 'partials/footer.php'; ?>
