<?php
require_once __DIR__ . '/mail-config.php';
require_once __DIR__ . '/helpers.php';

function send_booking_confirmation_email(array $booking): bool
{
    $to = $booking['email'];
    $subject = 'Your Dars Luxury Car Services Reservation is Confirmed';

    $lines = [];
    $lines[] = "Hi {$booking['full_name']},";
    $lines[] = '';
    $lines[] = 'Great news — your reservation request has been confirmed. Here are your trip details:';
    $lines[] = '';
    $lines[] = 'Service: ' . booking_service_label($booking['service_type']);
    $lines[] = 'Vehicle: ' . booking_car_label($booking['car']);
    $lines[] = 'Pickup Date: ' . date('l, F j, Y', strtotime($booking['pickup_date']));
    $lines[] = 'Pickup Time: ' . date('g:i A', strtotime($booking['pickup_time']));
    $lines[] = 'Pickup Location: ' . $booking['pickup_location'];
    $lines[] = 'Drop-off Location: ' . $booking['dropoff_location'];
    $lines[] = 'Passengers: ' . (int)$booking['passengers'];

    if (!empty($booking['return_trip'])) {
        $lines[] = '';
        $lines[] = 'Return Date: ' . date('l, F j, Y', strtotime($booking['return_date']));
        $lines[] = 'Return Time: ' . date('g:i A', strtotime($booking['return_time']));
    }

    if (!empty($booking['special_requests'])) {
        $lines[] = '';
        $lines[] = 'Special Requests: ' . $booking['special_requests'];
    }

    $lines[] = '';
    $lines[] = 'If anything above needs to change, just reply to this email or call us at 980-266-3343.';
    $lines[] = '';
    $lines[] = 'We look forward to driving you.';
    $lines[] = '';
    $lines[] = 'Dars Luxury Car Services';
    $lines[] = '105-F Waxhaw Professional Dr STE 600, Waxhaw, NC 28173';

    $body = implode("\r\n", $lines);

    $headers = [];
    $headers[] = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>';
    $headers[] = 'Reply-To: ' . MAIL_REPLY_TO;
    $headers[] = 'Content-Type: text/plain; charset=UTF-8';

    return mail($to, $subject, $body, implode("\r\n", $headers));
}
