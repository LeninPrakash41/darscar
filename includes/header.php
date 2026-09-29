<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | Dars Luxury Cars' : 'Dars Luxury Cars — Chauffeured Luxury Car Rental' ?></title>
<meta name="description" content="<?= isset($page_description) ? htmlspecialchars($page_description) : 'Dars Luxury Cars offers premium chauffeured car rental, airport transfers, corporate travel and event transportation.' ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a href="/index.php" class="brand">
      <span class="brand-mark">DARS</span>
      <span class="brand-sub">LUXURY CAR SERVICES</span>
    </a>

    <nav class="main-nav" id="mainNav">
      <a href="/index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">Home</a>
      <a href="/bentley-mulsanne.php" class="<?= $current_page === 'bentley-mulsanne.php' ? 'active' : '' ?>">Our Fleet</a>
      <a href="/booking.php" class="<?= $current_page === 'booking.php' ? 'active' : '' ?>">Booking</a>
      <a href="/contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">Contact Us</a>
    </nav>

    <a href="/booking.php" class="btn btn-gold header-cta">Book Now</a>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>
