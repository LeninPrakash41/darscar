-- Dars Luxury Cars database schema
-- Import with: mysql -u root -p darsluxurycars < sql/schema.sql
-- (create the database first: CREATE DATABASE darsluxurycars CHARACTER SET utf8mb4;)

CREATE TABLE IF NOT EXISTS bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    service_type VARCHAR(60) NOT NULL,
    car VARCHAR(100) NOT NULL,
    pickup_location VARCHAR(200) NOT NULL,
    dropoff_location VARCHAR(200) NOT NULL,
    pickup_date DATE NOT NULL,
    pickup_time TIME NOT NULL,
    passengers TINYINT UNSIGNED NOT NULL DEFAULT 1,
    luggage TINYINT UNSIGNED NOT NULL DEFAULT 0,
    return_trip TINYINT(1) NOT NULL DEFAULT 0,
    return_date DATE NULL,
    return_time TIME NULL,
    special_requests TEXT NULL,
    status ENUM('pending','approved','declined') NOT NULL DEFAULT 'pending',
    confirmed_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(60) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
