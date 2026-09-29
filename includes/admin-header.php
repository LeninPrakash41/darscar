<?php
require_once __DIR__ . '/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Admin' : 'Admin' ?> — Dars Luxury Car Services</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<?php if (!empty($_SESSION['admin_id'])): ?>
<header class="admin-topbar">
  <div class="container admin-topbar-inner">
    <a href="/admin/index.php" class="brand">
      <span class="brand-mark">DARS</span>
      <span class="brand-sub">ADMIN</span>
    </a>
    <nav class="admin-nav">
      <a href="/admin/index.php">Dashboard</a>
      <a href="/admin/bookings.php">Bookings</a>
      <a href="/admin/messages.php">Messages</a>
      <a href="/admin/change-password.php">Change Password</a>
    </nav>
    <div class="admin-user">
      <span><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></span>
      <a href="/admin/logout.php" class="btn btn-outline btn-sm">Log Out</a>
    </div>
  </div>
</header>
<?php endif; ?>

<main class="admin-main">
  <div class="container">
