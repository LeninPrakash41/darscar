-- Run this once against an existing database that was created before the admin panel was added.
-- mysql -u root -p darsluxurycars < sql/migration_001_admin_and_status.sql

ALTER TABLE bookings
    ADD COLUMN status ENUM('pending','approved','declined') NOT NULL DEFAULT 'pending' AFTER special_requests,
    ADD COLUMN confirmed_at TIMESTAMP NULL DEFAULT NULL AFTER status;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
