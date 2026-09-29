<?php
$page_title = 'Booking Received';
$page_description = 'Your booking request has been received by Dars Luxury Cars.';
require __DIR__ . '/includes/header.php';

$type = $_GET['type'] ?? 'booking';
?>

<section class="hero-page" style="min-height: 60vh; display:flex; align-items:center;">
  <div class="container" style="text-align:center; max-width:640px;">
    <span class="eyebrow">Request Received</span>
    <h1>Thank You<?= $type === 'booking' ? ' for Your Booking Request' : '' ?></h1>
    <p class="lead" style="margin:0 auto 32px;">
      <?php if ($type === 'booking'): ?>
        Your booking request has been submitted successfully. Our reservations team will contact you shortly to confirm availability and final details. No payment has been collected.
      <?php else: ?>
        Your message has been sent successfully. We'll be in touch soon.
      <?php endif; ?>
    </p>
    <a href="/index.php" class="btn btn-gold">Return to Home</a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
