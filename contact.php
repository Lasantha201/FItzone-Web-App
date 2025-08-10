<?php
require 'partials/header.php';
require 'config.php';

$alertMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);

  if ($name && $email && $message) {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
      $alertMessage = "<p class='form-success'>✅ Your message has been sent successfully!</p>";
    } else {
      $alertMessage = "<p class='form-error'>❌ Error sending your message. Please try again later.</p>";
    }
    $stmt->close();
  } else {
    $alertMessage = "<p class='form-error'>❗ Please fill in all required fields.</p>";
  }
}
?>

<!-- Contact Page Styles -->
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

  section.contact {
    padding-top: 180px;
    padding-bottom: 100px;
    background-color: var(--white);
    text-align: center;
  }

  .contact .section-title {
    color: var(--rich-black-fogra-29-1);
    margin-bottom: 20px;
  }

  .contact .section-subtitle {
    color: var(--coquelicot);
  }

  .contact-info {
    color: var(--rich-black-fogra-29-1);
    margin-bottom: 40px;
    font-size: var(--fs-6);
  }

  .contact-form {
    background-color: var(--white);
    max-width: 700px;
    margin: 0 auto;
    padding: 40px;
    border-radius: var(--radius-10);
    box-shadow: var(--shadow-1);
  }

  .contact-form input,
  .contact-form textarea {
    width: 100%;
    padding: 14px;
    margin-bottom: 20px;
    border-radius: var(--radius-6);
    border: 1px solid var(--gainsboro);
    font-size: var(--fs-6);
    font-family: var(--ff-catamaran);
  }

  .contact-form textarea {
    min-height: 120px;
    resize: vertical;
  }

  .contact-form button {
    background-color: var(--coquelicot);
    color: var(--white);
    border: none;
    padding: 12px 28px;
    font-weight: var(--fw-700);
    font-size: var(--fs-6);
    border-radius: var(--radius-6);
    cursor: pointer;
    transition: var(--transition-1);
  }

  .contact-form button:hover {
    background-color: var(--rich-black-fogra-29-1);
  }

  .form-success {
    color: green;
    font-size: var(--fs-6);
    margin-bottom: 20px;
  }

  .form-error {
    color: red;
    font-size: var(--fs-6);
    margin-bottom: 20px;
  }
</style>

<!-- Contact Section -->
<section class="contact section" id="contact">
  <div class="container">

    <center><p class="section-subtitle">We’d Love to Hear From You</p></center>
    <h2 class="section-title h2">Contact FitZone</h2>

    <div class="contact-info">
      <p>📍 63 Kandy Rd, Kurunegala 60000</p>
      <p>📞 0374 777 888</p>
      <p>✉️ support@fitzone.lk</p>
    </div>

    <div class="contact-form">
      <?= $alertMessage ?>
      <form method="POST" action="">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="text" name="subject" placeholder="Subject">
        <textarea name="message" placeholder="Your Message..." required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </div>

  </div>
</section>

<?php require 'partials/footer.php'; ?>
