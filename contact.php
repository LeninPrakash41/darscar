<?php
$page_title = 'Contact Us';
$page_description = 'Get in touch with Dars Luxury Cars for reservations, fleet questions or corporate accounts.';
require __DIR__ . '/includes/header.php';

$errors = $_SESSION['contact_errors'] ?? [];
$old = $_SESSION['contact_old'] ?? [];
$success = $_SESSION['contact_success'] ?? false;
unset($_SESSION['contact_errors'], $_SESSION['contact_old'], $_SESSION['contact_success']);

$field = function ($name, $default = '') use ($old) {
    return htmlspecialchars($old[$name] ?? $default);
};
?>

<section class="hero-page">
  <div class="container">
    <span class="eyebrow">Get In Touch</span>
    <h1>Contact Us</h1>
    <p class="lead">Questions about our fleet, rates or a custom itinerary? Send us a message and our reservations team will respond promptly.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="two-col">

      <div>
        <h3>Reservations Office</h3>
        <ul class="info-list">
          <li>
            <span class="icon">📞</span>
            <div><strong>Phone</strong><span><a href="tel:+19802663343">980-266-3343</a></span></div>
          </li>
          <li>
            <span class="icon">✉</span>
            <div><strong>Email</strong><span><a href="mailto:connectwithdars@gmail.com">connectwithdars@gmail.com</a></span></div>
          </li>
          <li>
            <span class="icon">📍</span>
            <div><strong>Address</strong><span>105-F Waxhaw Professional Dr STE 600<br>Waxhaw, NC 28173</span></div>
          </li>
          <li>
            <span class="icon">🎩</span>
            <div><strong>Reservations</strong><span>Ask for Kabelo</span></div>
          </li>
          <li>
            <span class="icon">🕐</span>
            <div><strong>Availability</strong><span>24 / 7 by Reservation</span></div>
          </li>
        </ul>
        <div class="img-placeholder" style="height:220px;">
          <span class="ph-icon">🗺</span>
          <span class="ph-label">Map / Location Image</span>
          <span class="ph-dims">Add image or embed — 600 × 400px</span>
        </div>
      </div>

      <div class="form-card">
        <?php if ($success): ?>
          <div class="alert alert-success">Thank you — your message has been sent. We'll be in touch shortly.</div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-error">
            <strong>Please correct the following:</strong>
            <ul style="margin: 8px 0 0 18px;">
              <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="/handlers/contact-handler.php" method="POST">
          <div class="form-grid">
            <div class="field required">
              <label for="full_name">Full Name</label>
              <input type="text" id="full_name" name="full_name" value="<?= $field('full_name') ?>" required>
            </div>
            <div class="field required">
              <label for="email">Email Address</label>
              <input type="email" id="email" name="email" value="<?= $field('email') ?>" required>
            </div>
            <div class="field full">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" value="<?= $field('phone') ?>">
            </div>
            <div class="field full required">
              <label for="subject">Subject</label>
              <input type="text" id="subject" name="subject" value="<?= $field('subject') ?>" required>
            </div>
            <div class="field full required">
              <label for="message">Message</label>
              <textarea id="message" name="message" required><?= $field('message') ?></textarea>
            </div>
          </div>
          <button type="submit" class="btn btn-gold btn-block" style="margin-top:24px;">Send Message</button>
        </form>
      </div>

    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
