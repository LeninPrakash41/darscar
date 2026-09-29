<?php
require_once __DIR__ . '/../includes/admin-auth.php';
require_admin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$pdo = get_db_connection();

$counts = $pdo->query(
    "SELECT
        COUNT(*) AS total,
        SUM(status = 'pending') AS pending,
        SUM(status = 'approved') AS approved,
        SUM(status = 'declined') AS declined
    FROM bookings"
)->fetch();

$upcoming = $pdo->query(
    "SELECT COUNT(*) FROM bookings
     WHERE status = 'approved' AND pickup_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)"
)->fetchColumn();

$recent = $pdo->query(
    'SELECT id, full_name, service_type, car, pickup_date, pickup_time, status, created_at
     FROM bookings ORDER BY created_at DESC LIMIT 8'
)->fetchAll();

$topService = $pdo->query(
    "SELECT service_type, COUNT(*) AS c FROM bookings GROUP BY service_type ORDER BY c DESC LIMIT 1"
)->fetch();

$unreadMessages = $pdo->query('SELECT COUNT(*) FROM contact_messages')->fetchColumn();

$page_title = 'Dashboard';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="page-head">
  <h1>Dashboard</h1>
</div>

<div class="stat-grid">
  <div class="stat-card">
    <span class="num"><?= (int)$counts['total'] ?></span>
    <span class="label">Total Bookings</span>
  </div>
  <div class="stat-card">
    <span class="num"><?= (int)$counts['pending'] ?></span>
    <span class="label">Pending Review</span>
  </div>
  <div class="stat-card">
    <span class="num"><?= (int)$counts['approved'] ?></span>
    <span class="label">Approved</span>
  </div>
  <div class="stat-card">
    <span class="num"><?= (int)$upcoming ?></span>
    <span class="label">Approved &middot; Next 7 Days</span>
  </div>
</div>

<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr);">
  <div class="stat-card">
    <span class="num"><?= (int)$counts['declined'] ?></span>
    <span class="label">Declined</span>
  </div>
  <div class="stat-card">
    <span class="num" style="font-size:20px;"><?= $topService ? htmlspecialchars(booking_service_label($topService['service_type'])) : '—' ?></span>
    <span class="label">Most Requested Service</span>
  </div>
  <div class="stat-card">
    <span class="num"><?= (int)$unreadMessages ?></span>
    <span class="label">Contact Messages</span>
  </div>
</div>

<div class="page-head">
  <h2 style="margin:0;">Recent Booking Requests</h2>
  <a href="/admin/bookings.php" class="btn btn-outline btn-sm">View All Bookings</a>
</div>

<div class="admin-table-wrap">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Customer</th>
        <th>Service</th>
        <th>Vehicle</th>
        <th>Pickup</th>
        <th>Status</th>
        <th>Submitted</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($recent)): ?>
        <tr><td colspan="7" class="muted">No booking requests yet.</td></tr>
      <?php else: foreach ($recent as $b): ?>
        <tr>
          <td><?= htmlspecialchars($b['full_name']) ?></td>
          <td><?= htmlspecialchars(booking_service_label($b['service_type'])) ?></td>
          <td><?= htmlspecialchars(booking_car_label($b['car'])) ?></td>
          <td><?= htmlspecialchars(date('M j, Y', strtotime($b['pickup_date']))) ?> at <?= htmlspecialchars(date('g:i A', strtotime($b['pickup_time']))) ?></td>
          <td><span class="badge <?= booking_status_class($b['status']) ?>"><?= booking_status_label($b['status']) ?></span></td>
          <td class="muted"><?= htmlspecialchars(date('M j, Y g:i A', strtotime($b['created_at']))) ?></td>
          <td><a href="/admin/booking-view.php?id=<?= (int)$b['id'] ?>" class="btn btn-outline btn-sm">View</a></td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
