<?php
require_once __DIR__ . '/../includes/admin-auth.php';
require_admin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$id = (int)($_GET['id'] ?? 0);
$pdo = get_db_connection();
$stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = :id');
$stmt->execute(['id' => $id]);
$b = $stmt->fetch();

if (!$b) {
    header('Location: /admin/bookings.php');
    exit;
}

$flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);

$page_title = 'Booking #' . $b['id'];
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="page-head">
  <h1>Booking #<?= (int)$b['id'] ?></h1>
  <span class="badge <?= booking_status_class($b['status']) ?>"><?= booking_status_label($b['status']) ?></span>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="form-card">
  <div class="detail-grid">
    <div class="item"><label>Full Name</label><span><?= htmlspecialchars($b['full_name']) ?></span></div>
    <div class="item"><label>Email</label><span><?= htmlspecialchars($b['email']) ?></span></div>
    <div class="item"><label>Phone</label><span><?= htmlspecialchars($b['phone']) ?></span></div>
    <div class="item"><label>Service</label><span><?= htmlspecialchars(booking_service_label($b['service_type'])) ?></span></div>
    <div class="item"><label>Vehicle</label><span><?= htmlspecialchars(booking_car_label($b['car'])) ?></span></div>
    <div class="item"><label>Passengers / Luggage</label><span><?= (int)$b['passengers'] ?> passengers, <?= (int)$b['luggage'] ?> bags</span></div>
    <div class="item full"><label>Pickup Location</label><span><?= htmlspecialchars($b['pickup_location']) ?></span></div>
    <div class="item full"><label>Drop-off Location</label><span><?= htmlspecialchars($b['dropoff_location']) ?></span></div>
    <div class="item"><label>Pickup Date</label><span><?= htmlspecialchars(date('l, F j, Y', strtotime($b['pickup_date']))) ?></span></div>
    <div class="item"><label>Pickup Time</label><span><?= htmlspecialchars(date('g:i A', strtotime($b['pickup_time']))) ?></span></div>
    <?php if ($b['return_trip']): ?>
      <div class="item"><label>Return Date</label><span><?= htmlspecialchars(date('l, F j, Y', strtotime($b['return_date']))) ?></span></div>
      <div class="item"><label>Return Time</label><span><?= htmlspecialchars(date('g:i A', strtotime($b['return_time']))) ?></span></div>
    <?php endif; ?>
    <?php if (!empty($b['special_requests'])): ?>
      <div class="item full"><label>Special Requests</label><span><?= nl2br(htmlspecialchars($b['special_requests'])) ?></span></div>
    <?php endif; ?>
    <div class="item"><label>Submitted</label><span><?= htmlspecialchars(date('M j, Y g:i A', strtotime($b['created_at']))) ?></span></div>
    <?php if ($b['confirmed_at']): ?>
      <div class="item"><label>Confirmed</label><span><?= htmlspecialchars(date('M j, Y g:i A', strtotime($b['confirmed_at']))) ?></span></div>
    <?php endif; ?>
  </div>

  <?php if ($b['status'] === 'pending'): ?>
    <div class="row-actions" style="gap:12px;">
      <form method="POST" action="/admin/booking-action.php" onsubmit="return confirm('Approve this booking and email the customer a confirmation?');">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
        <input type="hidden" name="action" value="approve">
        <input type="hidden" name="return_to" value="/admin/booking-view.php?id=<?= (int)$b['id'] ?>">
        <button type="submit" class="btn btn-gold">Approve &amp; Email Confirmation</button>
      </form>
      <form method="POST" action="/admin/booking-action.php" onsubmit="return confirm('Decline this booking?');">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
        <input type="hidden" name="action" value="decline">
        <input type="hidden" name="return_to" value="/admin/booking-view.php?id=<?= (int)$b['id'] ?>">
        <button type="submit" class="btn btn-danger">Decline</button>
      </form>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
