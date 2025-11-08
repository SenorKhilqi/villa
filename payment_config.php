<?php
/**
 * Payment Gateway Configuration
 * 
 * Configure your payment gateway credentials here.
 * Currently supports: Midtrans (for QRIS and other payment methods)
 * 
 * SECURITY: Never commit real credentials to git!
 * Use .env file for credentials (see .env.example)
 */

// Load environment variables from .env file
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

// Midtrans Configuration
// Get your credentials from: https://dashboard.midtrans.com/settings/config_info
// IMPORTANT: Set these in .env file, NOT here!
define('MIDTRANS_SERVER_KEY', getenv('MIDTRANS_SERVER_KEY') ?: '');
define('MIDTRANS_CLIENT_KEY', getenv('MIDTRANS_CLIENT_KEY') ?: '');
define('MIDTRANS_IS_PRODUCTION', getenv('MIDTRANS_IS_PRODUCTION') === 'true' ? true : false);
define('MIDTRANS_IS_SANITIZED', true);
define('MIDTRANS_IS_3DS', true);

// QRIS Settings
define('QRIS_EXPIRY_MINUTES', 30); // QR code expires after 30 minutes

// Refund Settings
define('REFUND_ALLOWED_HOURS_BEFORE', 48); // Users can request refund up to 48 hours before booking date
define('REFUND_PERCENTAGE', 50); // Refund 50% of payment if cancelled within allowed time

// Payment Gateway URLs
if (MIDTRANS_IS_PRODUCTION) {
    define('MIDTRANS_API_URL', 'https://api.midtrans.com/v2');
    define('MIDTRANS_SNAP_URL', 'https://app.midtrans.com/snap/snap.js');
} else {
    define('MIDTRANS_API_URL', 'https://api.sandbox.midtrans.com/v2');
    define('MIDTRANS_SNAP_URL', 'https://app.sandbox.midtrans.com/snap/snap.js');
}

// WhatsApp Configuration (for notifications)
define('WHATSAPP_ADMIN_NUMBER', '6289506892023');
define('WHATSAPP_ENABLED', true);

/**
 * Utility Functions
 */

// Check if payment gateway is configured
function isPaymentGatewayConfigured() {
    return MIDTRANS_SERVER_KEY !== 'YOUR_MIDTRANS_SERVER_KEY_HERE' 
        && MIDTRANS_CLIENT_KEY !== 'YOUR_MIDTRANS_CLIENT_KEY_HERE'
        && !empty(MIDTRANS_SERVER_KEY) 
        && !empty(MIDTRANS_CLIENT_KEY);
}

// Get base URL for the application
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    return $protocol . '://' . $host . rtrim($script, '/');
}

// Format currency for display
function formatCurrency($amount) {
    return 'Rp' . number_format($amount, 0, ',', '.');
}

// Calculate refund amount based on policy
function calculateRefundAmount($original_amount) {
    return $original_amount * (REFUND_PERCENTAGE / 100);
}

// Check if refund is allowed based on booking date
function isRefundAllowed($booking_date) {
    $booking_timestamp = strtotime($booking_date);
    $hours_until_booking = ($booking_timestamp - time()) / 3600;
    return $hours_until_booking >= REFUND_ALLOWED_HOURS_BEFORE;
}
?>
