<?php
require_once __DIR__ . '/../includes/admin-auth.php';
require_once __DIR__ . '/../includes/db.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: /admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $pdo = get_db_connection();
        $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: /admin/index.php');
            exit;
        }

        $error = 'Incorrect username or password.';
    }
}

$page_title = 'Admin Login';
require __DIR__ . '/../includes/admin-header.php';
?>

<div class="admin-login-wrap">
  <div class="section-head" style="text-align:left; margin-bottom:32px;">
    <span class="eyebrow">Dars Luxury Car Services</span>
    <h1>Admin Login</h1>
  </div>

  <?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="form-card">
    <form method="POST" action="/admin/login.php">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>">
      <div class="field required" style="margin-bottom:20px;">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus>
      </div>
      <div class="field required" style="margin-bottom:24px;">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-gold btn-block">Log In</button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/admin-footer.php'; ?>
