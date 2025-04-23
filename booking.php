<?php
require 'auth.php';

include 'config.php';

$booking_success = false; // Variabel untuk menandai keberhasilan booking
$booked_dates = []; // Array untuk menyimpan tanggal yang sudah dipesan

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
        // Dapatkan harga villa
        $villa_price_stmt = $conn->prepare("SELECT price FROM villas WHERE id = ?");
        $villa_price_stmt->bind_param("i", $villa_id);
        $villa_price_stmt->execute();
        $villa_price_stmt->bind_result($villa_price);
        $villa_price_stmt->fetch();
        $villa_price_stmt->close();

        // Tambahkan booking
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, villa_id, booking_date, payment_status, payment_method) VALUES (?, ?, ?, 'pending', ?)");
        $stmt->bind_param("iiss", $user_id, $villa_id, $booking_date, $payment_method);

        if ($stmt->execute()) {
            $booking_success = true; // Tandai bahwa booking berhasil
        
            // Pesan untuk WhatsApp
            $message = urlencode("Halo, saya telah melakukan booking villa pada tanggal $booking_date. Mohon konfirmasi.");
            $wa_url = "https://wa.me/6289506892023?text=$message";
            
            echo "<script>
                    alert('Booking berhasil! Anda akan diarahkan ke WhatsApp untuk konfirmasi.');
                    window.location.href = '$wa_url';
                  </script>";
        } else {
            echo "<script>alert('Gagal booking!');</script>"; // Notifikasi gagal
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


