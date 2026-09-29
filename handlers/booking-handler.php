<?php
session_start();
require __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /booking.php');
    exit;
}

$old = $_POST;
$errors = [];

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$service_type = trim($_POST['service_type'] ?? '');
$car = trim($_POST['car'] ?? '');
$pickup_location = trim($_POST['pickup_location'] ?? '');
$dropoff_location = trim($_POST['dropoff_location'] ?? '');
$pickup_date = trim($_POST['pickup_date'] ?? '');
$pickup_time = trim($_POST['pickup_time'] ?? '');
$passengers = (int)($_POST['passengers'] ?? 1);
$luggage = (int)($_POST['luggage'] ?? 0);
$return_trip = isset($_POST['return_trip']) ? 1 : 0;
$return_date = trim($_POST['return_date'] ?? '');
$return_time = trim($_POST['return_time'] ?? '');
$special_requests = trim($_POST['special_requests'] ?? '');

if ($full_name === '') $errors[] = 'Full name is required.';
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
if ($phone === '') $errors[] = 'Phone number is required.';
if (!in_array($service_type, ['airport_transfer', 'point_to_point', 'hourly', 'wedding', 'corporate', 'special_event'], true)) $errors[] = 'Please select a service type.';
if (!in_array($car, ['bentley-mulsanne', 'any'], true)) $errors[] = 'Please select a vehicle.';
if ($pickup_location === '') $errors[] = 'Pickup location is required.';
if ($dropoff_location === '') $errors[] = 'Drop-off location is required.';
if ($pickup_date === '' || !DateTime::createFromFormat('Y-m-d', $pickup_date)) $errors[] = 'A valid pickup date is required.';
if ($pickup_time === '') $errors[] = 'Pickup time is required.';
if ($passengers < 1 || $passengers > 4) $errors[] = 'Passengers must be between 1 and 4.';
if ($luggage < 0 || $luggage > 10) $errors[] = 'Luggage count must be between 0 and 10.';
if ($return_trip && ($return_date === '' || $return_time === '')) $errors[] = 'Return date and time are required for round trips.';

if (!empty($errors)) {
    $_SESSION['booking_errors'] = $errors;
    $_SESSION['booking_old'] = $old;
    header('Location: /booking.php');
    exit;
}

try {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare(
        'INSERT INTO bookings
        (full_name, email, phone, service_type, car, pickup_location, dropoff_location, pickup_date, pickup_time, passengers, luggage, return_trip, return_date, return_time, special_requests)
        VALUES
        (:full_name, :email, :phone, :service_type, :car, :pickup_location, :dropoff_location, :pickup_date, :pickup_time, :passengers, :luggage, :return_trip, :return_date, :return_time, :special_requests)'
    );

    $stmt->execute([
        'full_name' => $full_name,
        'email' => $email,
        'phone' => $phone,
        'service_type' => $service_type,
        'car' => $car,
        'pickup_location' => $pickup_location,
        'dropoff_location' => $dropoff_location,
        'pickup_date' => $pickup_date,
        'pickup_time' => $pickup_time,
        'passengers' => $passengers,
        'luggage' => $luggage,
        'return_trip' => $return_trip,
        'return_date' => $return_trip ? $return_date : null,
        'return_time' => $return_trip ? $return_time : null,
        'special_requests' => $special_requests !== '' ? $special_requests : null,
    ]);
} catch (PDOException $e) {
    $_SESSION['booking_errors'] = ['We could not save your booking right now. Please try again shortly or call us directly.'];
    $_SESSION['booking_old'] = $old;
    error_log('Booking insert failed: ' . $e->getMessage());
    header('Location: /booking.php');
    exit;
}

header('Location: /thank-you.php?type=booking');
exit;
