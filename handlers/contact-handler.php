<?php
session_start();
require __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact.php');
    exit;
}

$old = $_POST;
$errors = [];

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($full_name === '') $errors[] = 'Full name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
if ($subject === '') $errors[] = 'Subject is required.';
if ($message === '') $errors[] = 'Message is required.';

if (!empty($errors)) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_old'] = $old;
    header('Location: /contact.php');
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare(
        'INSERT INTO contact_messages (full_name, email, phone, subject, message)
        VALUES (:full_name, :email, :phone, :subject, :message)'
    );

    $stmt->execute([
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone !== '' ? $phone : null,
        'subject' => $subject,
        'message' => $message,
    ]);
} catch (PDOException $e) {
    $_SESSION['contact_errors'] = ['We could not send your message right now. Please try again shortly or call us directly.'];
    $_SESSION['contact_old'] = $old;
    error_log('Contact insert failed: ' . $e->getMessage());
    header('Location: /contact.php');
    exit;
}

$_SESSION['contact_success'] = true;
header('Location: /contact.php');
exit;
