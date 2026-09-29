<?php

function booking_service_label(string $key): string
{
    $labels = [
        'airport_transfer' => 'Airport Transfer',
        'point_to_point' => 'Point to Point',
        'hourly' => 'Hourly / As Directed',
        'wedding' => 'Wedding',
        'corporate' => 'Corporate Travel',
        'special_event' => 'Special Event',
    ];
    return $labels[$key] ?? $key;
}

function booking_car_label(string $key): string
{
    $labels = [
        'bentley-mulsanne' => '2018 Bentley Mulsanne',
        'any' => 'Any Available Vehicle',
    ];
    return $labels[$key] ?? $key;
}

function booking_status_label(string $status): string
{
    return ucfirst($status);
}

function booking_status_class(string $status): string
{
    $classes = [
        'pending' => 'badge-pending',
        'approved' => 'badge-approved',
        'declined' => 'badge-declined',
    ];
    return $classes[$status] ?? '';
}
