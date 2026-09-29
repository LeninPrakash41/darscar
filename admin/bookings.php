<?php
require_once __DIR__ . '/../includes/admin-auth.php';
require_admin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

$flash = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);

$statusFilter = $_GET['status'] ?? 'pending';
$allowed = ['pending', 'approved', 'declined', 'all'];
if (!in_array($statusFilter, $allowed, true)) {
    $statusFilter = 'pending';
}

$pdo = get_db_connection();

if ($statusFilter === 'all') {
    $stmt = $pdo->query('SELECT * FROM bookings ORDER BY created_at DESC');
} else {
    $stmt = $pdo->prepare('SELECT * FROM bookings WHERE status = :status ORDER BY created_at DESC');
    $stmt->execute(['status' => $statusFilter]);
}
$bookings = $stmt->fetchAll();

$page_title = 'Bookings';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="page-head">
  <h1>Booking Requests</h1>
</div>

<?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="filter-tabs">
  <a href="?status=pending" class="<?= $statusFilter === 'pending' ? 'active' : '' ?>">Pending</a>
  <a href="?status=approved" class="<?= $statusFilter === 'approved' ? 'active' : '' ?>">Approved</a>
  <a href="?status=declined" class="<?= $statusFilter === 'declined' ? 'active' : '' ?>">Declined</a>
  <a href="?status=all" class="<?= $statusFilter === 'all' ? 'active' : '' ?>">All</a>
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
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($bookings)): ?>
        <tr><td colspan="7" class="muted">No booking requests in this view.</td></tr>
      <?php else: foreach ($bookings as $b): ?>
        <tr>
          <td>
            <?= htmlspecialchars($b['full_name']) ?><br>
            <span class="muted"><?= htmlspecialchars($b['email']) ?></span>
          </td>
          <td><?= htmlspecialchars(booking_service_label($b['service_type'])) ?></td>
          <td><?= htmlspecialchars(booking_car_label($b['car'])) ?></td>
          <td><?= htmlspecialchars(date('M j, Y', strtotime($b['pickup_date']))) ?><br><span class="muted"><?= htmlspecialchars(date('g:i A', strtotime($b['pickup_time']))) ?></span></td>
          <td><span class="badge <?= booking_status_class($b['status']) ?>"><?= booking_status_label($b['status']) ?></span></td>
          <td class="muted"><?= htmlspecialchars(date('M j, Y', strtotime($b['created_at']))) ?></td>
          <td>
            <div class="row-actions">
              <a href="/admin/booking-view.php?id=<?= (int)$b['id'] ?>" class="btn btn-outline btn-sm">View</a>
              <?php if ($b['status'] === 'pending'): ?>
                <form method="POST" action="/admin/booking-action.php" onsubmit="return confirm('Approve this booking and email the customer a confirmation?');">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                  <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                  <input type="hidden" name="action" value="approve">
                  <input type="hidden" name="return_to" value="/admin/bookings.php?status=<?= htmlspecialchars($statusFilter) ?>">
                  <button type="submit" class="btn btn-gold btn-sm">Approve</button>
                </form>
                <form method="POST" action="/admin/booking-action.php" onsubmit="return confirm('Decline this booking?');">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
                  <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
                  <input type="hidden" name="action" value="decline">
                  <input type="hidden" name="return_to" value="/admin/bookings.php?status=<?= htmlspecialchars($statusFilter) ?>">
                  <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
