<?php
require 'auth.php';

include 'config.php';

// Ambil semua tanggal yang sudah dipesan
$booked_dates = [];
$stmt = $conn->prepare("SELECT booking_date FROM bookings");
$stmt->execute();
$stmt->bind_result($date);
while ($stmt->fetch()) {
    $booked_dates[] = $date; // Simpan semua tanggal yang sudah dipesan
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar - Villa Booking</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.menu').classList.toggle('show');
        });
    </script> 
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            background-image: url("kayu_hujung/IMG_46278.jpg");
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            padding-top: 50px;
        }
        /* Gaya Navbar */
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


        .calendar-container {
            width: 290px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Style untuk tanggal yang sudah dibooking */
        .ui-state-booked-date {
            background-color: #f44336 !important; /* Merah penuh */
            color: white !important;
            border-radius: 50%;
        }

        /* Untuk efek hover */
        .ui-datepicker .ui-state-booked-date:hover {
            background-color: #d32f2f !important;
            cursor: not-allowed;
        }
    </style>
    <script>
        $(function() {
            const bookedDates = <?php echo json_encode($booked_dates); ?>;
            
            // Membuat kalender dengan jQuery UI
            $("#calendar").datepicker({
                beforeShowDay: function(date) {
                    const string = $.datepicker.formatDate('yy-mm-dd', date);
                    
                    // Memeriksa apakah tanggal sudah dibooking
                    if (bookedDates.includes(string)) {
                        return [false, 'ui-state-booked-date', 'Tanggal sudah dipesan']; // Menandai tanggal yang sudah dipesan dengan warna merah
                    }
                    return [true, '', '']; // Tidak ada perubahan untuk tanggal yang tidak dibooking
                },
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
</head>
<body>
<nav class="navbar">  
        <div class="logo">  
            <img src="logo/kelompok.jpg" alt="Logo">  
        </div>  
        <button class="menu-toggle" aria-label="Toggle menu">☰</button>  
        <ul class="menu">  
            <li><a href="home.php">Beranda</a></li>  
            <li><a href="villa_kami.php">Villa Kami</a></li>  
            <li><a href="calender.php">Cek Tanggal</a></li>  
            <li><a href="tentang_kami.php">Tentang Kami</a></li>  
            <li><a href="logout.php">Logout</a></li>  
        </ul>  
    </nav>  
    <h2>Kalender Villa</h2>

    <div class="calendar-container">
        <div id="calendar"></div>
    </div>

</body>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</html>
