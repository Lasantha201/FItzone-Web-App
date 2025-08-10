<?php require 'partials/header.php'; ?>

<!-- Terms and Conditions Custom Styles -->
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

  .terms {
    padding-top: 160px;
    padding-bottom: 100px;
    background-color: var(--white);
    color: var(--rich-black-fogra-29-1);
    font-family: var(--ff-catamaran);
  }

  .terms .container {
    max-width: 900px;
    margin: 0 auto;
    padding-inline: 20px;
  }

  .terms h2 {
    font-size: var(--fs-2);
    color: var(--coquelicot);
    margin-bottom: 20px;
    text-align: center;
  }

  .terms h3 {
    font-size: var(--fs-4);
    margin-top: 30px;
    margin-bottom: 10px;
    color: var(--rich-black-fogra-29-1);
  }

  .terms p {
    font-size: var(--fs-6);
    line-height: 1.7;
    margin-bottom: 16px;
  }

  .terms ul {
    list-style: disc;
    padding-left: 20px;
    margin-bottom: 20px;
  }
</style>


<!-- Terms & Conditions Section -->
<section class="terms section" id="terms">
  <div class="container">
    <h2>Terms & Conditions</h2>

    <p>Welcome to FitZone! By using our website and services, you agree to the following terms and conditions. Please read them carefully.</p>

    <h3>1. Use of Our Services</h3>
    <p>All users must be at least 16 years old or have parental consent to register or use FitZone services. You agree to provide accurate and complete information at all times.</p>

    <h3>2. Membership & Payments</h3>
    <ul>
      <li>Memberships are personal and non-transferable.</li>
      <li>All payments must be made in full before services are rendered.</li>
      <li>Refunds are subject to management approval based on case-specific policies.</li>
    </ul>

    <h3>3. Health Disclaimer</h3>
    <p>You should consult your physician before starting any fitness program. FitZone is not liable for any injuries or health issues that arise from using our facilities or training services.</p>

    <h3>4. Code of Conduct</h3>
    <p>We expect respectful behavior toward trainers, staff, and other clients. Any abuse, damage, or misconduct may result in suspension or cancellation of membership.</p>

    <h3>5. Intellectual Property</h3>
    <p>All content, including text, logos, images, and videos, is the property of FitZone. You may not reproduce or use it without written permission.</p>

    <h3>6. Termination</h3>
    <p>FitZone reserves the right to terminate accounts or access to services at any time for violations of these terms or harmful behavior.</p>

    <h3>7. Changes to Terms</h3>
    <p>We may update these terms from time to time. Changes will be posted on this page and take effect immediately upon posting.</p>

    <p><strong>Last updated:</strong> July 2025</p>
  </div>
</section>

<?php require 'partials/footer.php'; ?>
