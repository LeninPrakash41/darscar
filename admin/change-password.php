<?php
require_once __DIR__ . '/../includes/admin-auth.php';
require_admin();
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $pdo = get_db_connection();
        $stmt = $pdo->prepare('SELECT password_hash FROM admins WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['admin_id']]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($current, $admin['password_hash'])) {
            $errors[] = 'Current password is incorrect.';
        }
        if (strlen($new) < 8) {
            $errors[] = 'New password must be at least 8 characters.';
        }
        if ($new !== $confirm) {
            $errors[] = 'New password and confirmation do not match.';
        }

        if (empty($errors)) {
            $update = $pdo->prepare('UPDATE admins SET password_hash = :hash WHERE id = :id');
            $update->execute([
                'hash' => password_hash($new, PASSWORD_DEFAULT),
                'id' => $_SESSION['admin_id'],
            ]);
            $success = true;
        }
    }
}

$page_title = 'Change Password';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="page-head">
  <h1>Change Password</h1>
</div>

<?php if ($success): ?>
  <div class="alert alert-success">Your password has been updated.</div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error">
    <ul style="margin: 0 0 0 18px;">
      <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="form-card" style="max-width:480px;">
  <form method="POST" action="/admin/change-password.php">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
    <div class="field required" style="margin-bottom:20px;">
      <label for="current_password">Current Password</label>
      <input type="password" id="current_password" name="current_password" required>
    </div>
    <div class="field required" style="margin-bottom:20px;">
      <label for="new_password">New Password</label>
      <input type="password" id="new_password" name="new_password" required minlength="8">
    </div>
    <div class="field required" style="margin-bottom:24px;">
      <label for="confirm_password">Confirm New Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
    </div>
    <button type="submit" class="btn btn-gold btn-block">Update Password</button>
  </form>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
