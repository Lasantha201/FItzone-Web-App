<?php require 'partials/header.php'; ?>

<!-- Pricing Page Custom Styles -->
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

  section.pricing {
    padding-top: 200px;
  }

  .pricing-card {
    background-color: var(--white);
    padding: 32px 24px;
    border-radius: var(--radius-10);
    box-shadow: var(--shadow-1);
    transition: var(--transition-1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .pricing-card:hover {
    transform: translateY(-5px);
  }

  .pricing-header {
    text-align: center;
    margin-bottom: 24px;
  }

  .pricing-title {
    font-size: var(--fs-4);
    color: var(--rich-black-fogra-29-1);
    margin-bottom: 10px;
  }

  .pricing-price {
    font-size: var(--fs-2);
    font-weight: var(--fw-800);
    color: var(--coquelicot);
  }

  .pricing-features {
    list-style: none;
    padding: 0;
    margin: 20px 0;
    color: var(--sonic-silver);
    font-size: var(--fs-6);
  }

  .pricing-features li {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .pricing-btn {
    background-color: var(--coquelicot);
    color: var(--white);
    padding: 10px 16px;
    border-radius: var(--radius-6);
    text-align: center;
    font-weight: var(--fw-700);
    text-decoration: none;
    transition: var(--transition-1);
  }

  .pricing-btn:hover {
    background-color: var(--rich-black-fogra-29-1);
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
  }
</style>

<!-- Pricing Section -->
<section class="pricing section" id="pricing">
  <div class="container">

    <center><p class="section-subtitle">Membership Plans</p></center>
    <center><h2 class="h2 section-title">Choose Your Plan</h2></center>

    <div class="grid">

      <!-- Basic Plan -->
      <div class="pricing-card">
        <div class="pricing-header">
          <h3 class="pricing-title">Basic</h3>
          <p class="pricing-price">Rs. 3,000 /mo</p>
        </div>
        <ul class="pricing-features">
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>Access to gym equipment</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>Locker facility</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>1 free fitness class / week</li>
        </ul>
        <a href="membership.php?plan=Basic" class="pricing-btn">Get Started</a>
      </div>

      <!-- Standard Plan -->
      <div class="pricing-card">
        <div class="pricing-header">
          <h3 class="pricing-title">Standard</h3>
          <p class="pricing-price">Rs. 5,000 /mo</p>
        </div>
        <ul class="pricing-features">
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>Everything in Basic</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>3 classes / week</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>Diet consultation</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>Personal locker</li>
        </ul>
        <a href="membership.php?plan=Standard" class="pricing-btn">Get Started</a>
      </div>

      <!-- Premium Plan -->
      <div class="pricing-card">
        <div class="pricing-header">
          <h3 class="pricing-title">Premium</h3>
          <p class="pricing-price">Rs. 8,000 /mo</p>
        </div>
        <ul class="pricing-features">
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>All Standard Features</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>Unlimited classes</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>1-on-1 coaching</li>
          <li><ion-icon name="checkmark-circle-outline"></ion-icon>Massage & recovery zone access</li>
        </ul>
        <a href="membership.php?plan=Premium" class="pricing-btn">Join Premium</a>
      </div>

    </div>
  </div>
</section>

<?php require 'partials/footer.php'; ?>
