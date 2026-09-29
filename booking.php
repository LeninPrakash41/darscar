<?php
$page_title = 'Book a Car';
$page_description = 'Reserve your chauffeured luxury car with Dars Luxury Cars. No payment required to submit a booking request.';
require __DIR__ . '/includes/header.php';

$errors = $_SESSION['booking_errors'] ?? [];
$old = $_SESSION['booking_old'] ?? [];
unset($_SESSION['booking_errors'], $_SESSION['booking_old']);

$field = function ($name, $default = '') use ($old) {
    return htmlspecialchars($old[$name] ?? $default);
};
$selected = function ($name, $value) use ($old) {
    return (($old[$name] ?? '') === $value) ? 'selected' : '';
};
$checked = function ($name) use ($old) {
    return !empty($old[$name]) ? 'checked' : '';
};
?>

<section class="hero-page">
  <div class="container">
    <span class="eyebrow">Reserve Your Ride</span>
    <h1>Book a Car</h1>
    <p class="lead">Fill out the form below and our reservations team will confirm your booking by phone or email. No payment is required to submit a request.</p>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width: 900px;">

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

    <div class="form-card">
      <form action="/handlers/booking-handler.php" method="POST">

        <h3 style="margin-bottom:20px;">Contact Details</h3>
        <div class="form-grid">
          <div class="field required">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?= $field('full_name') ?>" required>
          </div>
          <div class="field required">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= $field('email') ?>" required>
          </div>
          <div class="field required">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?= $field('phone') ?>" required>
          </div>
          <div class="field required">
            <label for="service_type">Service Type</label>
            <select id="service_type" name="service_type" required>
              <option value="" disabled <?= empty($old['service_type']) ? 'selected' : '' ?>>Select a service</option>
              <option value="airport_transfer" <?= $selected('service_type', 'airport_transfer') ?>>Airport Transfer</option>
              <option value="point_to_point" <?= $selected('service_type', 'point_to_point') ?>>Point to Point</option>
              <option value="hourly" <?= $selected('service_type', 'hourly') ?>>Hourly / As Directed</option>
              <option value="wedding" <?= $selected('service_type', 'wedding') ?>>Wedding</option>
              <option value="corporate" <?= $selected('service_type', 'corporate') ?>>Corporate Travel</option>
              <option value="special_event" <?= $selected('service_type', 'special_event') ?>>Special Event</option>
            </select>
          </div>
        </div>

        <h3 style="margin:32px 0 20px;">Trip Details</h3>
        <div class="form-grid">
          <div class="field required">
            <label for="car">Select Vehicle</label>
            <select id="car" name="car" required>
              <option value="bentley-mulsanne" <?= $selected('car', 'bentley-mulsanne') ?>>2018 Bentley Mulsanne</option>
              <option value="any" <?= $selected('car', 'any') ?>>Any Available Vehicle</option>
            </select>
          </div>
          <div class="field required">
            <label for="passengers">Passengers</label>
            <input type="number" id="passengers" name="passengers" min="1" max="4" value="<?= $field('passengers', '1') ?>" required>
          </div>
          <div class="field full required">
            <label for="pickup_location">Pickup Location</label>
            <input type="text" id="pickup_location" name="pickup_location" placeholder="Address, airport or hotel name" value="<?= $field('pickup_location') ?>" required>
          </div>
          <div class="field full required">
            <label for="dropoff_location">Drop-off Location</label>
            <input type="text" id="dropoff_location" name="dropoff_location" placeholder="Address, airport or hotel name" value="<?= $field('dropoff_location') ?>" required>
          </div>
          <div class="field required">
            <label for="pickup_date">Pickup Date</label>
            <input type="date" id="pickup_date" name="pickup_date" value="<?= $field('pickup_date') ?>" required>
          </div>
          <div class="field required">
            <label for="pickup_time">Pickup Time</label>
            <input type="time" id="pickup_time" name="pickup_time" value="<?= $field('pickup_time') ?>" required>
          </div>
          <div class="field">
            <label for="luggage">Number of Luggage</label>
            <input type="number" id="luggage" name="luggage" min="0" max="10" value="<?= $field('luggage', '0') ?>">
          </div>
          <div class="field">
            <div class="checkbox-row" style="margin-top:30px;">
              <input type="checkbox" id="return_trip" name="return_trip" value="1" <?= $checked('return_trip') ?>>
              <label for="return_trip">This is a round trip</label>
            </div>
          </div>

          <div class="form-grid full" id="returnFields" style="display:none; grid-column: 1 / -1;">
            <div class="field">
              <label for="return_date">Return Date</label>
              <input type="date" id="return_date" name="return_date" value="<?= $field('return_date') ?>">
            </div>
            <div class="field">
              <label for="return_time">Return Time</label>
              <input type="time" id="return_time" name="return_time" value="<?= $field('return_time') ?>">
            </div>
          </div>

          <div class="field full">
            <label for="special_requests">Special Requests</label>
            <textarea id="special_requests" name="special_requests" placeholder="Child seat, flight number, meet-and-greet sign, etc."><?= $field('special_requests') ?></textarea>
          </div>
        </div>

        <button type="submit" class="btn btn-gold btn-block" style="margin-top:32px;">Submit Booking Request</button>
        <p class="form-note">This is a booking request, not a confirmed reservation. Our team will contact you to confirm availability. No payment information is collected on this site.</p>
      </form>
    </div>

  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
