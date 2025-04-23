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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        /* Style dasar untuk body */
        body {
    font-family: 'Arial', sans-serif;
    background-image: url('Kayu_hujung/IMG_4590.jpg'); /* Ganti dengan path gambar Anda */
    background-size: cover; /* Agar gambar menutupi seluruh area */
    background-position: center; /* Agar gambar berada di tengah */
    margin: 0;
}

        /* Style untuk judul halaman */
        h2 {
            text-align: center;
            color: #333;
            padding-top: 70px;
        }

        /* Style untuk navbar */
        .navbar {  
            display: flex;  
            justify-content: space-between;  
            align-items: center;  
            background-color: #B0A695;  
        }  
        .logo img {  
            max-width: 70px;  
            height: auto;
            padding-left: 30px;
        }  
        .menu {  
            list-style: none;  
            display: flex;  
            margin: 0;  
            padding: 0;  
        }  
        .menu li {  
            margin: 0 15px;  
        }  
        .menu a {  
            font-family: Arial, Helvetica, sans-serif;
            text-decoration: none;  
            color: #ffffff;  
            font-weight: 20px;  
        }  
        .menu a:hover {  
            color: #6b5b4a;  
            text-decoration: underline;  
        }  
        .menu-toggle {  
            display: none;  
            font-size: 24px;  
            background: none;  
            border: none;  
            cursor: pointer;  
        }  

        @media (max-width: 768px) {  
            .menu {  
                display: none;  
                flex-direction: column;  
                position: absolute;  
                top: 60px;  
                right: 0;  
                background-color: #B0A695;  
                width: 100%;  
                padding: 10px 0;  
            }  
            .menu li {  
                margin: 10px 0;  
            }  
            .menu-toggle {  
                display: block;  
            }  
            .navbar {  
                position: relative;  
            }  
        }  

        .menu.show {  
            display: flex;  
        }

        /* Style untuk form */
        form {
            background-color: rgba(255, 255, 255, 0.8); /* Mengubah latar belakang menjadi setengah transparan */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 10px auto;
        }

        /* Konsistensi untuk select, input, dan button */
        select, input[type="text"], button {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: 2px solid #ccc; /* Sama untuk semua elemen */
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.5);
        }

        /* Fokus style */
        select:focus, input[type="text"]:focus, button:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        /* Button style */
        button {
            width: 300px; /* Lebar tetap, bisa disesuaikan sesuai keinginan */
            padding: 12px;
            background-color:  #B0A695;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
            display: block; /* Agar center alignment bisa berfungsi */
            margin: 0 auto; /* Center alignment */
            margin-top: 100px;
        }

        /* Hover efek untuk button */
        button:hover {
            background-color: #B0A695;
        }

        /* Gaya untuk tanggal yang sudah dibooking */
        .ui-state-booked-date {
            background-color: #f44336 !important; /* Warna merah */
            color: white !important;
        }

        .ui-datepicker .ui-state-booked-date {
            border-radius: 50%;
        }

        .ui-datepicker .ui-state-booked-date:hover {
            background-color: #d32f2f; /* Warna merah gelap saat hover */
            cursor: not-allowed; /* Menonaktifkan pointer cursor */
        }

    </style>
    <script>
        $(function() {
            const bookedDates = <?php echo json_encode($booked_dates); ?>;
            $("#booking_date").datepicker({
                beforeShowDay: function(date) {
                    const string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    // Cek jika tanggal sudah dipesan
                    if (bookedDates.includes(string)) {
                        return [true, 'ui-state-booked-date', 'Tanggal sudah dipesan']; // Gunakan kelas 'ui-state-booked-date'
                    }
                    return [true, '', '']; // Kembalikan tanpa kelas khusus
                },
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">  
        <div class="logo">  
        <img src="logo/kelompok.jpg" alt="Logo">  
        </div>  
        <button class="menu-toggle" aria-label="Toggle menu">☰</button>  
        <ul class="menu">  
            <li><a href="home.php">Beranda</a></li>  
            <li><a href="villa_kami.php">Villa Kami</a></li>  
            <li><a href="booking.php">Cek Tanggal</a></li>  
            <li><a href="tentang_kami.php">Tentang Kami</a></li>  
            <li><a href="logout.php">Logout</a></li>  
        </ul>  
    </nav>  

    <h2>Booking Villa</h2>

    <!-- Form Booking -->
    <form action="booking.php" method="POST">
        <label for="villa">Pilih Villa:</label>
        <select name="villa_id" id="villa" required>
            <?php while ($villa = $villas->fetch_assoc()): ?>
                <option value="<?= $villa['id']; ?>"><?= $villa['name']; ?> - Rp. <?= number_format($villa['price'], 0, ',', '.'); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="booking_date">Pilih Tanggal:</label>
        <input type="text" id="booking_date" name="booking_date" required>

        <label for="payment_method">Metode Pembayaran:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="dana">Dana</option>
            <option value="ovo">OVO</option>
            <option value="gopay">GoPay</option>
        </select>

        <button type="submit">Pesan Villa</button>
    </form>
</body>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</html>


