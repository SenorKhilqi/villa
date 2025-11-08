<?php
/**
 * Midtrans Payment Notification Handler (Webhook)
 * 
 * This file receives payment notifications from Midtrans when payment status changes
 * URL to configure in Midtrans dashboard: https://yourdomain.com/payment_notification.php
 */

require_once 'config.php';
require_once 'payment_gateway.php';

// Get notification data from Midtrans
$json_result = file_get_contents('php://input');
$notification = json_decode($json_result, true);

// Log the notification for debugging
$log_file = 'payment_notifications.log';
file_put_contents($log_file, date('Y-m-d H:i:s') . " - " . $json_result . "\n", FILE_APPEND);

// Verify signature
if (!PaymentGateway::verifySignature($notification)) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
    exit;
}

$order_id = $notification['order_id'];
$transaction_status = $notification['transaction_status'];
$fraud_status = $notification['fraud_status'] ?? 'accept';
$payment_type = $notification['payment_type'];

// Extract booking_id from order_id (format: VILLA-{booking_id}-{timestamp})
preg_match('/VILLA-(\d+)-/', $order_id, $matches);
if (!isset($matches[1])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid order ID format']);
    exit;
}

$booking_id = (int) $matches[1];

// Get booking details
$stmt = $conn->prepare("SELECT user_id, villa_id, payment_status FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Booking not found']);
    exit;
}

// Determine new payment status based on Midtrans notification
$new_payment_status = $booking['payment_status'];
$paid_at = null;

if ($transaction_status == 'capture') {
    if ($fraud_status == 'accept') {
        $new_payment_status = 'completed';
        $paid_at = date('Y-m-d H:i:s');
    }
} else if ($transaction_status == 'settlement') {
    $new_payment_status = 'completed';
    $paid_at = date('Y-m-d H:i:s');
} else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
    $new_payment_status = 'expired';
} else if ($transaction_status == 'pending') {
    $new_payment_status = 'awaiting_payment';
}

// Update booking status
if ($paid_at) {
    $update_stmt = $conn->prepare("UPDATE bookings SET payment_status = ?, paid_at = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_payment_status, $paid_at, $booking_id);
} else {
    $update_stmt = $conn->prepare("UPDATE bookings SET payment_status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_payment_status, $booking_id);
}

$update_stmt->execute();
$update_stmt->close();

// Log the payment event
$event_type = 'payment_' . $transaction_status;
$event_data = json_encode($notification);

$log_stmt = $conn->prepare("INSERT INTO payment_logs (booking_id, event_type, event_data) VALUES (?, ?, ?)");
$log_stmt->bind_param("iss", $booking_id, $event_type, $event_data);
$log_stmt->execute();
$log_stmt->close();

// Send response to Midtrans
http_response_code(200);
echo json_encode(['status' => 'success', 'message' => 'Notification processed']);
?>
