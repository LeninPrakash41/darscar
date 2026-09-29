<?php
// Outgoing mail settings for booking confirmation emails.
// MAIL_FROM_ADDRESS should ideally be an address on your own hosting domain
// (e.g. reservations@darsluxurycars.com) — sending "From" a Gmail address via
// PHP's mail() will usually be flagged as spoofed and land in spam or get rejected,
// since your server isn't authorized to send on Gmail's behalf.
// MAIL_REPLY_TO is where customer replies should go — this can safely be your Gmail.
define('MAIL_FROM_ADDRESS', 'reservations@darsluxurycars.com');
define('MAIL_FROM_NAME', 'Dars Luxury Car Services');
define('MAIL_REPLY_TO', 'connectwithdars@gmail.com');
