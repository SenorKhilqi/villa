<?php
require 'auth.php';
include 'config.php';
require_once 'payment_gateway.php';

$booking_success = false; // Variabel untuk menandai keberhasilan booking
$booked_dates = []; // Array untuk menyimpan tanggal yang sudah dipesan
$qr_code_url = null; // For QRIS QR code URL
$show_qr_modal = false; // Flag to show QR code modal

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Ambil data villa termasuk harga dan gambar
$villas = $conn->query("SELECT * FROM villas");

// Ambil semua tanggal yang sudah dipesan
$stmt = $conn->prepare("SELECT booking_date FROM bookings");
$stmt->execute();
$stmt->bind_result($date);
while ($stmt->fetch()) {
    $booked_dates[] = $date; // Simpan semua tanggal yang sudah dipesan
}
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_date = $_POST['booking_date'];
    $villa_id = $_POST['villa_id'];
    $payment_method = $_POST['payment_method'];
    $user_id = $_SESSION['user_id'];

    // Cek apakah tanggal yang dipilih sudah dipesan
    if (in_array($booking_date, $booked_dates)) {
        echo "<script>alert('Villa sudah dipesan pada tanggal tersebut.');</script>";
    } else {
        // Dapatkan harga villa dan nama villa
        $villa_price_stmt = $conn->prepare("SELECT price, name FROM villas WHERE id = ?");
        $villa_price_stmt->bind_param("i", $villa_id);
        $villa_price_stmt->execute();
        $villa_price_stmt->bind_result($villa_price, $villa_name);
        $villa_price_stmt->fetch();
        $villa_price_stmt->close();

        // Tambahkan booking dengan status 'awaiting_payment' untuk QRIS otomatis
        $initial_status = ($payment_method === 'qris' && isPaymentGatewayConfigured()) ? 'awaiting_payment' : 'pending';
        
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, villa_id, booking_date, payment_status, payment_method) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $villa_id, $booking_date, $initial_status, $payment_method);

        if ($stmt->execute()) {
            $booking_id = $conn->insert_id;
            $booking_success = true;
            
            // If QRIS is selected and payment gateway is configured, generate QR code
            if ($payment_method === 'qris' && isPaymentGatewayConfigured()) {
                $qris_result = PaymentGateway::generateQRIS($booking_id, $user_id, $villa_name, $villa_price);
                
                if ($qris_result['success']) {
                    // Update booking with QRIS details
                    $update_stmt = $conn->prepare("UPDATE bookings SET qr_code_url = ?, payment_reference = ? WHERE id = ?");
                    $update_stmt->bind_param("ssi", $qris_result['qr_code_url'], $qris_result['payment_reference'], $booking_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                    
                    // Show QR code modal instead of redirecting to WhatsApp
                    $qr_code_url = $qris_result['qr_code_url'];
                    $show_qr_modal = true;
                    
                    echo "<script>
                            alert('Booking berhasil! Silakan scan QR Code untuk melakukan pembayaran.');
                          </script>";
                } else {
                    // Failed to generate QRIS, fallback to manual WhatsApp confirmation
                    echo "<script>
                            alert('Booking berhasil, namun gagal generate QRIS: {$qris_result['message']}. Anda akan diarahkan ke WhatsApp untuk konfirmasi manual.');
                          </script>";
                    $message = urlencode("Halo, saya telah melakukan booking villa $villa_name pada tanggal $booking_date. Mohon konfirmasi pembayaran QRIS.");
                    $wa_url = "https://wa.me/6289506892023?text=$message";
                    echo "<script>window.location.href = '$wa_url';</script>";
                }
            } else {
                // Non-QRIS payment methods - redirect to WhatsApp as before
                $message = urlencode("Halo, saya telah melakukan booking villa $villa_name pada tanggal $booking_date via $payment_method. Mohon konfirmasi.");
                $wa_url = "https://wa.me/6289506892023?text=$message";
                
                echo "<script>
                        alert('Booking berhasil! Anda akan diarahkan ke WhatsApp untuk konfirmasi.');
                        window.location.href = '$wa_url';
                      </script>";
            }
        } else {
            echo "<script>alert('Gagal booking!');</script>";
        }
        
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Villa</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --primary: #B0A695;
            --primary-light: #D4C8BE;
            --primary-dark: #8A7F6C;
            --accent: #776B5D;
            --light: #F5F3EF;
            --dark: #383330;
            --danger: #FF6B6B;
            --success: #6BCB77;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('kayu_hujung/IMG_4590.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }
        
        .page-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding-bottom: 50px;
        }
        
        .page-title {
            text-align: center;
            color: white;
            padding: 80px 0 40px;
        }
        
        .page-title h1 {
            font-size: 42px;
            margin: 0;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .page-title p {
            font-size: 18px;
            margin: 15px 0 0;
            font-weight: 300;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .booking-container {
            max-width: 650px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .booking-form {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark);
            font-size: 17px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            color: var(--dark);
            background-color: white;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(176, 166, 149, 0.2);
            outline: none;
        }
        
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23777' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }
        
        .form-title {
            font-size: 24px;
            margin-top: 0;
            margin-bottom: 30px;
            color: var(--accent);
            text-align: center;
            position: relative;
            padding-bottom: 15px;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--primary);
            border-radius: 3px;
        }
        
        #booking_date {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23777' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
            cursor: pointer;
        }
        
        .btn-submit {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 18px;
            cursor: pointer;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(138, 127, 108, 0.3);
            margin-top: 10px;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(138, 127, 108, 0.4);
        }
        
        .ui-state-booked-date {
            background-color: var(--danger) !important;
            color: white !important;
            border-radius: 50%;
            opacity: 0.8;
        }
        
        .ui-datepicker .ui-state-booked-date:hover {
            background-color: var(--danger) !important;
            cursor: not-allowed;
            opacity: 1;
        }
        
        footer {
            background-color: #8d7b4f;
            color: white;
            text-align: center;
            padding: 25px 20px;
            margin-top: auto;
        }
        
        footer p {
            margin: 0;
            font-weight: 300;
        }
        
        @media (max-width: 768px) {
            .page-title {
                padding: 50px 0 30px;
            }
            
            .page-title h1 {
                font-size: 32px;
            }
            
            .page-title p {
                font-size: 16px;
            }
            
            .booking-form {
                padding: 25px;
            }
            
            .form-title {
                font-size: 22px;
            }
            
            .form-label {
                font-size: 15px;
            }
            
            .form-control {
                padding: 10px 12px;
                font-size: 15px;
            }
            
            .btn-submit {
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="page-wrapper">
        <div class="page-title">
            <h1 data-aos="fade-up">Booking Villa</h1>
            <p data-aos="fade-up" data-aos-delay="200">Isi formulir di bawah ini untuk memesan villa pada tanggal yang Anda inginkan</p>
        </div>
        
        <div class="booking-container">
            <div class="booking-form" data-aos="fade-up" data-aos-delay="400">
                <h2 class="form-title">Formulir Pemesanan</h2>
                
                <form action="booking.php" method="POST">
                    <div class="form-group">
                        <label for="villa" class="form-label">Pilih Villa:</label>
                        <select name="villa_id" id="villa" class="form-control" required>
                            <?php while ($villa = $villas->fetch_assoc()): ?>
                                <option value="<?= $villa['id']; ?>"><?= $villa['name']; ?> - Rp. <?= number_format($villa['price'], 0, ',', '.'); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="booking_date" class="form-label">Pilih Tanggal:</label>
                        <input type="text" id="booking_date" name="booking_date" class="form-control" required readonly placeholder="Klik untuk memilih tanggal">
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_method" class="form-label">Metode Pembayaran:</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                            <option value="qris">QRIS</option>
                            <option value="dana">Dana</option>
                            <option value="ovo">OVO</option>
                            <option value="gopay">GoPay</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit">Pesan Sekarang</button>
                </form>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024. All Rights Reserved Villa Situ Lengkong Panjalu</p>
    </footer>

    <!-- QRIS Payment Modal -->
    <?php if ($show_qr_modal && $qr_code_url): ?>
    <div id="qrisModal" class="qris-modal">
        <div class="qris-modal-content">
            <div class="qris-modal-header">
                <h2>Scan QR Code untuk Pembayaran</h2>
                <span class="qris-close">&times;</span>
            </div>
            <div class="qris-modal-body">
                <div class="qr-code-container">
                    <img src="<?php echo htmlspecialchars($qr_code_url); ?>" alt="QRIS QR Code" class="qr-code-image">
                </div>
                <div class="payment-instructions">
                    <h3>Cara Pembayaran:</h3>
                    <ol>
                        <li>Buka aplikasi e-wallet Anda (Dana, OVO, GoPay, ShopeePay, LinkAja, dll)</li>
                        <li>Pilih menu "Scan QR" atau "QRIS"</li>
                        <li>Scan QR Code di atas</li>
                        <li>Konfirmasi pembayaran sebesar <strong>Rp<?php echo number_format($villa_price, 0, ',', '.'); ?></strong></li>
                        <li>Pembayaran akan otomatis terverifikasi</li>
                    </ol>
                    <div class="payment-note">
                        <i class="fas fa-info-circle"></i>
                        <p>QR Code ini berlaku selama <strong>30 menit</strong>. Setelah pembayaran berhasil, status booking Anda akan otomatis diupdate.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button onclick="window.location.href='home.php'" class="btn-secondary">Saya Sudah Bayar</button>
                    <button onclick="checkPaymentStatus()" class="btn-primary">Cek Status Pembayaran</button>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .qris-modal {
            display: block;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }
        
        .qris-modal-content {
            background-color: white;
            margin: 3% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .qris-modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 25px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .qris-modal-header h2 {
            margin: 0;
            font-size: 22px;
        }
        
        .qris-close {
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .qris-close:hover {
            transform: scale(1.2);
        }
        
        .qris-modal-body {
            padding: 30px;
        }
        
        .qr-code-container {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .qr-code-image {
            max-width: 300px;
            width: 100%;
            height: auto;
            border: 3px solid var(--primary);
            border-radius: 10px;
            padding: 10px;
            background: white;
        }
        
        .payment-instructions {
            margin-bottom: 25px;
        }
        
        .payment-instructions h3 {
            color: var(--accent);
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .payment-instructions ol {
            padding-left: 20px;
        }
        
        .payment-instructions li {
            margin-bottom: 10px;
            line-height: 1.6;
        }
        
        .payment-note {
            background-color: #fff3cd;
            border-left: 4px solid var(--warning);
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            display: flex;
            gap: 10px;
        }
        
        .payment-note i {
            color: var(--warning);
            font-size: 20px;
        }
        
        .payment-note p {
            margin: 0;
            color: #856404;
        }
        
        .modal-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .modal-actions button {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .modal-actions .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .modal-actions .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .modal-actions .btn-secondary {
            background-color: #f8f9fa;
            color: var(--text-dark);
            border: 2px solid var(--border-color);
        }
        
        .modal-actions .btn-secondary:hover {
            background-color: #e9ecef;
        }
        
        @media (max-width: 768px) {
            .qris-modal-content {
                width: 95%;
                margin: 10% auto;
            }
            
            .qris-modal-body {
                padding: 20px;
            }
            
            .modal-actions {
                flex-direction: column;
            }
            
            .modal-actions button {
                width: 100%;
            }
        }
    </style>
    
    <script>
        // Close modal when clicking X
        document.querySelector('.qris-close')?.addEventListener('click', function() {
            document.getElementById('qrisModal').style.display = 'none';
            window.location.href = 'home.php';
        });
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('qrisModal');
            if (event.target == modal) {
                modal.style.display = 'none';
                window.location.href = 'home.php';
            }
        }
        
        function checkPaymentStatus() {
            alert('Mengecek status pembayaran...');
            // In production, this would call payment_check.php via AJAX
            window.location.href = 'home.php';
        }
    </script>
    <?php endif; ?>

    <script src="js/navbar.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        $(function() {
            const bookedDates = <?php echo json_encode($booked_dates); ?>;
            
            $("#booking_date").datepicker({
                beforeShowDay: function(date) {
                    const string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    
                    if (bookedDates.includes(string)) {
                        return [false, 'ui-state-booked-date', 'Tanggal sudah dipesan'];
                    }
                    return [true, '', ''];
                },
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                minDate: 0
            });
            
            $("#booking_date").on('click', function() {
                $(this).datepicker('show');
            });
        });
        
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: true
        });
    </script>
</body>
</html>


