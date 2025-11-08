<?php
/**
 * Pembatalan Booking Page
 * Allows users to ajukan pembatalan for their bookings
 */

require 'auth.php';
include 'config.php';
require_once 'payment_config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Handle refund request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_refund'])) {
    $booking_id = (int) $_POST['booking_id'];
    $refund_reason = trim($_POST['refund_reason']);
    $refund_method = trim($_POST['refund_method']);
    $account_holder_name = trim($_POST['account_holder_name']);
    $account_number = trim($_POST['account_number']);
    
    // Validate booking belongs to user
    $check_stmt = $conn->prepare("SELECT b.id, b.booking_date, b.payment_status, v.price, b.refund_status 
                                   FROM bookings b 
                                   JOIN villas v ON b.villa_id = v.id 
                                   WHERE b.id = ? AND b.user_id = ?");
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $booking = $result->fetch_assoc();
    $check_stmt->close();
    
    if (!$booking) {
        $message = 'Booking tidak ditemukan atau bukan milik Anda.';
        $message_type = 'error';
    } elseif ($booking['payment_status'] !== 'completed') {
        $message = 'Hanya booking yang sudah dibayar yang bisa dibatalkan.';
        $message_type = 'error';
    } elseif ($booking['refund_status'] !== 'none') {
        $message = 'Pembatalan sudah pernah diajukan untuk booking ini.';
        $message_type = 'error';
    } else {
        // Check if refund is allowed based on booking date
        if (!isRefundAllowed($booking['booking_date'])) {
            $message = 'Pembatalan hanya bisa diajukan minimal ' . REFUND_ALLOWED_HOURS_BEFORE . ' jam sebelum tanggal booking.';
            $message_type = 'error';
        } else {
            // Calculate refund amount using helper function
            $refund_amount = calculateRefundAmount($booking['price']);
            
            // Insert refund request with payment details
            $refund_stmt = $conn->prepare("INSERT INTO refunds (booking_id, refund_amount, refund_reason, refund_method, account_holder_name, account_number, requested_by_user_id, refund_status) 
                                           VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
            $refund_stmt->bind_param("idssssi", $booking_id, $refund_amount, $refund_reason, $refund_method, $account_holder_name, $account_number, $user_id);
            
            if ($refund_stmt->execute()) {
                // Update booking refund status with payment details
                $update_stmt = $conn->prepare("UPDATE bookings SET refund_status = 'requested', refund_amount = ?, refund_reason = ?, refund_method = ?, refund_account_holder = ?, refund_account_number = ?, refund_requested_at = NOW() WHERE id = ?");
                $update_stmt->bind_param("dssssi", $refund_amount, $refund_reason, $refund_method, $account_holder_name, $account_number, $booking_id);
                $update_stmt->execute();
                $update_stmt->close();
                
                $message = 'Pembatalan berhasil diajukan! Admin akan meninjau permintaan Anda.';
                $message_type = 'success';
            } else {
                $message = 'Gagal mengajukan pembatalan. Silakan coba lagi.';
                $message_type = 'error';
            }
            $refund_stmt->close();
        }
    }
}

// Get user's bookings that are eligible untuk pembatalan
$bookings_stmt = $conn->prepare("
    SELECT b.id, v.name AS villa_name, v.price, b.booking_date, b.payment_status, b.refund_status, b.paid_at,
           TIMESTAMPDIFF(HOUR, NOW(), b.booking_date) AS hours_until_booking
    FROM bookings b
    JOIN villas v ON b.villa_id = v.id
    WHERE b.user_id = ? AND b.payment_status = 'completed'
    ORDER BY b.booking_date DESC
");
$bookings_stmt->bind_param("i", $user_id);
$bookings_stmt->execute();
$bookings_result = $bookings_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Pembatalan - Villa Situ Lengkong</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #B0A695;
            --primary-dark: #8A7F6C;
            --accent: #776B5D;
            --success: #6BCB77;
            --danger: #FF6B6B;
            --warning: #FFD166;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 0 20px;
        }
        
        .page-title {
            font-size: 32px;
            color: var(--accent);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid var(--success);
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid var(--danger);
        }
        
        .bookings-grid {
            display: grid;
            gap: 20px;
        }
        
        .booking-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .booking-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
        }
        
        .villa-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--accent);
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        
        .status-none { background-color: #e9ecef; color: #495057; }
        .status-requested { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d1ecf1; color: #0c5460; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        
        .booking-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        
        .detail-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-weight: 500;
            color: #212529;
        }
        
        .refund-form {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            margin-top: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--accent);
        }
        
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            resize: vertical;
            min-height: 100px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #e55555;
            transform: translateY(-2px);
        }
        
        .btn-danger:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .refund-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .refund-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .no-bookings {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .no-bookings i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }
        
        .refund-payment-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 2px dashed var(--primary);
        }
        
        .refund-payment-section h4 {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <h1 class="page-title">Pengajuan Pembatalan</h1>
        
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $message_type; ?>">
            <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
        
        <div class="bookings-grid">
            <?php if ($bookings_result->num_rows > 0): ?>
                <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3 class="villa-name"><?php echo htmlspecialchars($booking['villa_name']); ?></h3>
                            <span class="status-badge status-<?php echo $booking['refund_status']; ?>">
                                <?php 
                                $status_labels = [
                                    'none' => 'Belum Dibatalkan',
                                    'requested' => 'Menunggu Review',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'completed' => 'Selesai'
                                ];
                                echo $status_labels[$booking['refund_status']];
                                ?>
                            </span>
                        </div>
                        
                        <div class="booking-details">
                            <div class="detail-item">
                                <span class="detail-label">Tanggal Booking</span>
                                <span class="detail-value"><?php echo date('d M Y', strtotime($booking['booking_date'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Harga</span>
                                <span class="detail-value">Rp<?php echo number_format($booking['price'], 0, ',', '.'); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Dibayar</span>
                                <span class="detail-value">
                                    <?php 
                                    if (!empty($booking['paid_at'])) {
                                        echo date('d M Y H:i', strtotime($booking['paid_at']));
                                    } else {
                                        echo 'Menunggu konfirmasi';
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Waktu Tersisa</span>
                                <span class="detail-value">
                                    <?php 
                                    $hours = (int) $booking['hours_until_booking'];
                                    if ($hours > 0) {
                                        echo $hours . ' jam';
                                    } else {
                                        echo 'Sudah berlalu';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                        
                        <?php if ($booking['refund_status'] === 'none' && $booking['hours_until_booking'] >= REFUND_ALLOWED_HOURS_BEFORE): ?>
                        <div class="refund-form">
                            <div class="refund-info">
                                <p><i class="fas fa-info-circle"></i> <strong>Informasi Pembatalan:</strong></p>
                                <p>• Jumlah pengembalian: <strong>Rp<?php echo number_format($booking['price'] * (REFUND_PERCENTAGE / 100), 0, ',', '.'); ?></strong> (<?php echo REFUND_PERCENTAGE; ?>% dari total)</p>
                                <p>• Dana akan diproses dalam 3-7 hari kerja setelah disetujui</p>
                            </div>
                            
                            <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan pembatalan?');">
                                <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                
                                <div class="form-group">
                                    <label class="form-label">Alasan Pembatalan *</label>
                                    <textarea name="refund_reason" required placeholder="Jelaskan alasan Anda mengajukan pembatalan..."></textarea>
                                </div>
                                
                                <div class="refund-payment-section">
                                    <h4 style="color: var(--accent); margin-bottom: 15px; font-size: 16px;">
                                        <i class="fas fa-wallet"></i> Detail Pengembalian Dana
                                    </h4>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Metode Pengembalian *</label>
                                        <select name="refund_method" id="refund_method" required style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-family: 'Poppins', sans-serif;">
                                            <option value="">-- Pilih Metode Pengembalian --</option>
                                            <option value="dana">Dana</option>
                                            <option value="ovo">OVO</option>
                                            <option value="gopay">GoPay</option>
                                            <option value="bank_bca">Transfer Bank BCA</option>
                                            <option value="bank_mandiri">Transfer Bank Mandiri</option>
                                            <option value="bank_bri">Transfer Bank BRI</option>
                                            <option value="bank_bni">Transfer Bank BNI</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Nama Pemegang Akun *</label>
                                        <input type="text" name="account_holder_name" required placeholder="Nama sesuai akun e-wallet/rekening" style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-family: 'Poppins', sans-serif;">
                                        <small style="color: #6c757d; font-size: 12px; display: block; margin-top: 5px;">
                                            Pastikan nama sesuai dengan akun yang terdaftar
                                        </small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Nomor Akun/Rekening *</label>
                                        <input type="text" name="account_number" id="account_number" required placeholder="08xxx atau nomor rekening" style="width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 8px; font-family: 'Poppins', sans-serif;">
                                        <small id="account_hint" style="color: #6c757d; font-size: 12px; display: block; margin-top: 5px;">
                                            Masukkan nomor HP untuk e-wallet atau nomor rekening untuk transfer bank
                                        </small>
                                    </div>
                                </div>
                                
                                <button type="submit" name="request_refund" class="btn btn-danger">
                                    <i class="fas fa-undo"></i> Ajukan Pembatalan
                                </button>
                            </form>
                        </div>
                        <?php elseif ($booking['hours_until_booking'] < REFUND_ALLOWED_HOURS_BEFORE && $booking['refund_status'] === 'none'): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            Pembatalan tidak bisa diajukan karena kurang dari <?php echo REFUND_ALLOWED_HOURS_BEFORE; ?> jam sebelum tanggal booking.
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-bookings">
                    <i class="fas fa-calendar-times"></i>
                    <p>Belum ada booking yang bisa dibatalkan.</p>
                    <p>Hanya booking yang sudah dibayar yang muncul di sini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="js/navbar.js"></script>
    
    <script>
        // Dynamic hint untuk account number based on refund method
        document.getElementById('refund_method')?.addEventListener('change', function() {
            const hint = document.getElementById('account_hint');
            const accountInput = document.getElementById('account_number');
            const method = this.value;
            
            if (method === 'dana' || method === 'ovo' || method === 'gopay') {
                hint.textContent = 'Masukkan nomor HP yang terdaftar di ' + method.toUpperCase() + ' (contoh: 08123456789)';
                accountInput.placeholder = '08xxx';
            } else if (method.startsWith('bank_')) {
                const bank = method.replace('bank_', '').toUpperCase();
                hint.textContent = 'Masukkan nomor rekening ' + bank + ' Anda';
                accountInput.placeholder = 'Nomor rekening ' + bank;
            } else {
                hint.textContent = 'Masukkan nomor HP untuk e-wallet atau nomor rekening untuk transfer bank';
                accountInput.placeholder = '08xxx atau nomor rekening';
            }
        });
    </script>
</body>
</html>

<?php
$bookings_stmt->close();
$conn->close();
?>

