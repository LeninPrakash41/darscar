<?php
require_once __DIR__ . '/../includes/admin-auth.php';
require_admin();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/mailer.php';

$returnTo = $_POST['return_to'] ?? '/admin/bookings.php';
if (strpos($returnTo, '/admin/') !== 0) {
    $returnTo = '/admin/bookings.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf($_POST['csrf_token'] ?? null)) {
    $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Your session expired. Please try again.'];
    header('Location: ' . $returnTo);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!in_array($action, ['approve', 'decline'], true) || $id <= 0) {
    $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Invalid request.'];
    header('Location: ' . $returnTo);
    exit;
}

$pdo = get_db_connection();
$stmt = $pdo->prepare('SELECT * FROM bookings WHERE id = :id');
$stmt->execute(['id' => $id]);
$booking = $stmt->fetch();

if (!$booking) {
    $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'Booking not found.'];
    header('Location: ' . $returnTo);
    exit;
}

if ($booking['status'] !== 'pending') {
    $_SESSION['admin_flash'] = ['type' => 'error', 'message' => 'This booking has already been ' . $booking['status'] . '.'];
    header('Location: ' . $returnTo);
    exit;
}

$newStatus = $action === 'approve' ? 'approved' : 'declined';

$update = $pdo->prepare(
    'UPDATE bookings SET status = :status, confirmed_at = :confirmed_at WHERE id = :id'
);
$update->execute([
    'status' => $newStatus,
    'confirmed_at' => $newStatus === 'approved' ? date('Y-m-d H:i:s') : null,
    'id' => $id,
]);

if ($newStatus === 'approved') {
    $sent = send_booking_confirmation_email($booking);
    $_SESSION['admin_flash'] = $sent
        ? ['type' => 'success', 'message' => 'Booking approved and confirmation email sent to ' . $booking['email'] . '.']
        : ['type' => 'error', 'message' => 'Booking approved, but the confirmation email could not be sent. Please follow up with the customer manually.'];
} else {
    $_SESSION['admin_flash'] = ['type' => 'success', 'message' => 'Booking declined.'];
}

header('Location: ' . $returnTo);
exit;
