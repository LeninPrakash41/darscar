<?php
// CLI-only helper to create or reset an admin login.
// Usage: php scripts/create-admin.php <username> <password>

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('This script can only be run from the command line.');
}

require_once __DIR__ . '/../includes/db.php';

$username = $argv[1] ?? null;
$password = $argv[2] ?? null;

if (!$username || !$password) {
    fwrite(STDERR, "Usage: php scripts/create-admin.php <username> <password>\n");
    exit(1);
}

if (strlen($password) < 8) {
    fwrite(STDERR, "Password must be at least 8 characters.\n");
    exit(1);
}

$pdo = get_db_connection();
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare(
    'INSERT INTO admins (username, password_hash) VALUES (:username, :hash)
     ON DUPLICATE KEY UPDATE password_hash = :hash2'
);
$stmt->execute(['username' => $username, 'hash' => $hash, 'hash2' => $hash]);

echo "Admin account '{$username}' created/updated successfully.\n";
