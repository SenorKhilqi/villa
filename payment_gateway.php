<?php
/**
 * Payment Gateway Integration Library
 * 
 * This library handles QRIS generation and payment verification using Midtrans
 * Docs: https://docs.midtrans.com/
 */

require_once 'payment_config.php';

class PaymentGateway {
    
    /**
     * Generate QRIS payment for a booking
     * 
     * @param int $booking_id Booking ID
     * @param int $user_id User ID
     * @param string $villa_name Villa name
     * @param float $amount Payment amount
     * @return array Result with success status, qr_code_url, and payment_reference
     */
    public static function generateQRIS($booking_id, $user_id, $villa_name, $amount) {
        if (!isPaymentGatewayConfigured()) {
            return [
                'success' => false,
                'message' => 'Payment gateway not configured. Please set MIDTRANS credentials in payment_config.php'
            ];
        }

        // Generate unique order ID
        $order_id = 'VILLA-' . $booking_id . '-' . time();
        
        // Prepare transaction details
        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => (int) $amount
        ];
        
        $item_details = [
            [
                'id' => 'villa_' . $booking_id,
                'price' => (int) $amount,
                'quantity' => 1,
                'name' => 'Booking ' . $villa_name
            ]
        ];
        
        $customer_details = [
            'user_id' => $user_id
        ];
        
        // Enable QRIS only
        $enabled_payments = ['qris'];
        
        $transaction = [
            'payment_type' => 'qris',
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
            'enabled_payments' => $enabled_payments,
            'expiry' => [
                'unit' => 'minutes',
                'duration' => QRIS_EXPIRY_MINUTES
            ],
            'callbacks' => [
                'finish' => getBaseUrl() . '/payment_success.php?booking_id=' . $booking_id
            ]
        ];
        
        // Call Midtrans API to create transaction
        $result = self::callMidtransAPI('/charge', $transaction);
        
        if ($result['success']) {
            $response_data = $result['data'];
            
            // Extract QR code URL from response
            $qr_code_url = null;
            if (isset($response_data['actions'])) {
                foreach ($response_data['actions'] as $action) {
                    if ($action['name'] === 'generate-qr-code') {
                        $qr_code_url = $action['url'];
                        break;
                    }
                }
            }
            
            return [
                'success' => true,
                'qr_code_url' => $qr_code_url,
                'payment_reference' => $order_id,
                'transaction_id' => $response_data['transaction_id'] ?? null,
                'expiry_time' => $response_data['expiry_time'] ?? null
            ];
        }
        
        return [
            'success' => false,
            'message' => $result['message'] ?? 'Failed to generate QRIS'
        ];
    }
    
    /**
     * Check payment status from Midtrans
     * 
     * @param string $order_id Order ID to check
     * @return array Payment status information
     */
    public static function checkPaymentStatus($order_id) {
        if (!isPaymentGatewayConfigured()) {
            return [
                'success' => false,
                'message' => 'Payment gateway not configured'
            ];
        }
        
        $result = self::callMidtransAPI('/' . $order_id . '/status', null, 'GET');
        
        if ($result['success']) {
            $data = $result['data'];
            $transaction_status = $data['transaction_status'] ?? 'unknown';
            $fraud_status = $data['fraud_status'] ?? 'accept';
            
            $is_paid = false;
            if ($transaction_status === 'capture' || $transaction_status === 'settlement') {
                if ($fraud_status === 'accept') {
                    $is_paid = true;
                }
            }
            
            return [
                'success' => true,
                'is_paid' => $is_paid,
                'transaction_status' => $transaction_status,
                'fraud_status' => $fraud_status,
                'payment_data' => $data
            ];
        }
        
        return [
            'success' => false,
            'message' => $result['message'] ?? 'Failed to check payment status'
        ];
    }
    
    /**
     * Process refund via Midtrans
     * 
     * @param string $order_id Original order ID
     * @param float $refund_amount Amount to refund
     * @param string $reason Refund reason
     * @return array Refund result
     */
    public static function processRefund($order_id, $refund_amount, $reason = '') {
        if (!isPaymentGatewayConfigured()) {
            return [
                'success' => false,
                'message' => 'Payment gateway not configured'
            ];
        }
        
        $refund_data = [
            'refund_amount' => (int) $refund_amount,
            'reason' => $reason
        ];
        
        $result = self::callMidtransAPI('/' . $order_id . '/refund', $refund_data, 'POST');
        
        if ($result['success']) {
            return [
                'success' => true,
                'refund_id' => $result['data']['refund_id'] ?? null,
                'message' => 'Refund processed successfully'
            ];
        }
        
        return [
            'success' => false,
            'message' => $result['message'] ?? 'Failed to process refund'
        ];
    }
    
    /**
     * Call Midtrans API
     * 
     * @param string $endpoint API endpoint
     * @param array|null $data Request data
     * @param string $method HTTP method (GET or POST)
     * @return array API response
     */
    private static function callMidtransAPI($endpoint, $data = null, $method = 'POST') {
        $url = MIDTRANS_API_URL . $endpoint;
        
        $ch = curl_init();
        
        // Set authorization header
        $auth = base64_encode(MIDTRANS_SERVER_KEY . ':');
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . $auth
        ];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        
        if ($method === 'POST' && $data !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        if ($curl_error) {
            return [
                'success' => false,
                'message' => 'CURL Error: ' . $curl_error
            ];
        }
        
        $response_data = json_decode($response, true);
        
        if ($http_code >= 200 && $http_code < 300) {
            return [
                'success' => true,
                'data' => $response_data
            ];
        }
        
        return [
            'success' => false,
            'message' => $response_data['status_message'] ?? 'API Error: HTTP ' . $http_code,
            'data' => $response_data
        ];
    }
    
    /**
     * Verify Midtrans notification signature
     * Used for webhook/callback verification
     * 
     * @param array $notification_data Notification data from Midtrans
     * @return bool True if signature is valid
     */
    public static function verifySignature($notification_data) {
        $order_id = $notification_data['order_id'] ?? '';
        $status_code = $notification_data['status_code'] ?? '';
        $gross_amount = $notification_data['gross_amount'] ?? '';
        $server_key = MIDTRANS_SERVER_KEY;
        
        $signature_key = hash('sha512', $order_id . $status_code . $gross_amount . $server_key);
        $received_signature = $notification_data['signature_key'] ?? '';
        
        return $signature_key === $received_signature;
    }
}
?>
