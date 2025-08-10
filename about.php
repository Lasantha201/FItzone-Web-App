<?php require 'partials/header.php'; ?>

<!-- about Page Custom Styles -->
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

  section#about {
    padding-top: 200px;
  }
</style>


<!-- About Section -->
<section class="about section" id="about">
  <div class="container">

    <div class="about-banner">
      <img src="assets/images/about.jpg" alt="FitZone Gym" class="w-100">
    </div>

    <div class="about-content">
      <p class="section-subtitle">About Us</p>
      <h2 class="h2 section-title">Build Strength, Build Life</h2>

      <p class="section-text">
        FitZone Fitness Center in Kurunegala is dedicated to helping you become your strongest self — physically and mentally.
        We believe fitness is not just a routine, but a way to build confidence, discipline, and a better life.
      </p>

      <p class="section-text">
        Our facility is equipped with modern training equipment and run by certified professionals who support your personal journey.
        We offer programs in cardio, strength, yoga, and overall wellness in a safe, friendly environment.
      </p>

      <p class="section-text">
        FitZone is open 24/7 and welcomes individuals of all fitness levels. Whether you're just getting started or pushing past your limits,
        our team is here to guide and support you every step of the way.
      </p>
      
    </div>

  </div>
</section>

<?php require 'partials/footer.php'; ?>
