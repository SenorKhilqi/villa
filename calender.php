<?php
require 'auth.php';
include 'config.php';

// Ambil semua tanggal yang sudah dipesan
$booked_dates = [];
$stmt = $conn->prepare("SELECT booking_date FROM bookings");
$stmt->execute();
$stmt->bind_result($date);
while ($stmt->fetch()) {
    $booked_dates[] = $date;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Availability Calendar</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        :root {
            --primary: #B0A695;
            --primary-light: #EBE3D5;
            --primary-dark: #776B5D;
            --text-dark: #333333;
            --text-light: #777777;
            --white: #FFFFFF;
            --danger: #e57373;
            --danger-dark: #af4448;
            --accent-green: #81b29a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background-color: #f8f9fa;
        }
        
        /* Hero section with parallax effect */
        .hero {
            height: 60vh;
            min-height: 400px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 120%;
            background-image: url('kayu_hujung/IMG_4624.jpg');
            background-size: cover;
            background-position: center 20%;
            transform: translateZ(0);
            z-index: -2;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.6));
            z-index: -1;
        }
        
        .hero-content {
            color: var(--white);
            max-width: 800px;
            padding: 0 20px;
            position: relative;
        }
        
        .hero-subtitle {
            font-family: 'Poppins', sans-serif;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            line-height: 1.2;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .hero-description {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Main content */
        .main-content {
            position: relative;
            margin-top: -80px;
            padding-bottom: 80px;
        }
        
        .calendar-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .calendar-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }
        
        .calendar-sidebar {
            flex: 1;
            min-width: 300px;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: var(--white);
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .sidebar-text {
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        
        .calendar-legend {
            margin-top: auto;
        }
        
        .legend-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .legend-color {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            margin-right: 12px;
        }
        
        .color-available {
            background-color: var(--white);
        }
        
        .color-selected {
            background-color: var(--accent-green);
        }
        
        .color-booked {
            background-color: var(--danger);
        }
        
        /* Calendar main area */
        .calendar-main {
            flex: 2;
            min-width: 350px;
            padding: 40px;
        }
        
        #calendar {
            width: 100%;
        }
        
        /* Booking button */
        .booking-btn-container {
            text-align: center;
            margin-top: 40px;
        }
        
        .booking-btn {
            display: inline-flex;
            align-items: center;
            padding: 15px 30px;
            background-color: var(--primary);
            color: var(--white);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(176, 166, 149, 0.3);
        }
        
        .booking-btn svg {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }
        
        .booking-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(176, 166, 149, 0.4);
        }

        /* Custom styling for datepicker */
        .ui-datepicker {
            width: 100% !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }
        
        .ui-datepicker-header {
            background: var(--primary-light) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 15px !important;
            margin-bottom: 15px;
        }
        
        .ui-datepicker-title {
            font-family: 'Playfair Display', serif;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 18px;
        }
        
        .ui-datepicker-prev, .ui-datepicker-next {
            top: 15px !important;
            cursor: pointer;
            border-radius: 50%;
            background: var(--white) !important;
            width: 30px !important;
            height: 30px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s ease;
            opacity: 0.7;
        }
        
        .ui-datepicker-prev:hover, .ui-datepicker-next:hover {
            background: var(--white) !important;
            opacity: 1;
            transform: scale(1.1);
        }
        
        .ui-datepicker table {
            font-size: 15px;
        }
        
        .ui-datepicker th {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: var(--text-light);
            padding: 10px 0;
        }
        
        .ui-datepicker td {
            padding: 3px;
        }
        
        .ui-datepicker td span, .ui-datepicker td a {
            text-align: center !important;
            padding: 10px !important;
            border-radius: 8px !important;
            border: none !important;
            transition: all 0.2s ease;
        }
        
        .ui-datepicker td a.ui-state-default {
            background-color: transparent !important;
            color: var(--text-dark) !important;
        }
        
        .ui-datepicker td a.ui-state-default:hover {
            background-color: var(--primary-light) !important;
            color: var(--text-dark) !important;
        }
        
        .ui-datepicker td a.ui-state-active {
            background-color: var(--accent-green) !important;
            color: var(--white) !important;
        }
        
        /* Style for booked dates */
        .ui-state-booked-date {
            background-color: var(--danger) !important;
            color: var(--white) !important;
            border-radius: 8px;
            position: relative;
        }
        
        .ui-state-booked-date::before {
            content: '✕';
            position: absolute;
            font-size: 10px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        
        .ui-datepicker .ui-state-booked-date:hover {
            background-color: var(--danger-dark) !important;
            cursor: not-allowed;
        }

        /* Footer */
        footer {
            background-color: #8d7b4f;
            color: var(--white);
            text-align: center;
            padding: 30px 20px;
        }
        
        footer p {
            margin: 0;
            font-size: 14px;
        }

        /* Responsive styles */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 42px;
            }
            
            .calendar-sidebar {
                padding: 30px 25px;
            }
            
            .calendar-main {
                padding: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .hero {
                height: 50vh;
                min-height: 350px;
            }
            
            .hero-title {
                font-size: 36px;
            }
            
            .hero-description {
                font-size: 16px;
            }
            
            .calendar-card {
                flex-direction: column;
            }
            
            .calendar-sidebar {
                width: 100%;
                order: 2;
                border-radius: 0 0 20px 20px;
            }
            
            .calendar-main {
                order: 1;
            }
            
            .calendar-legend {
                margin-top: 20px;
                margin-bottom: 0;
            }
        }
        
        @media (max-width: 480px) {
            .hero-title {
                font-size: 28px;
            }
            
            .main-content {
                margin-top: -60px;
            }
            
            .calendar-main {
                padding: 25px 20px;
            }
            
            .sidebar-title {
                font-size: 24px;
            }
        }

        /* Parallax scroll effect with CSS */
        .parallax-scroll {
            position: relative;
            overflow: hidden;
        }

        /* Animation for page loading */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-up {
            animation: fadeUp 0.8s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .delay-3 {
            animation-delay: 0.6s;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Hero Section with Parallax -->
    <section class="hero parallax-scroll">
        <div class="hero-bg" id="parallax-hero"></div>
        <div class="hero-content">
            <p class="hero-subtitle fade-up">Villa Situ Lengkong</p>
            <h1 class="hero-title fade-up delay-1">Kalender Ketersediaan</h1>
            <p class="hero-description fade-up delay-2">Periksa tanggal yang tersedia untuk booking villa impian Anda</p>
        </div>
    </section>

    <!-- Main Calendar Section -->
    <section class="main-content">
        <div class="calendar-container">
            <div class="calendar-card fade-up delay-3">
                <div class="calendar-sidebar">
                    <h2 class="sidebar-title">Cek Tanggal</h2>
                    <p class="sidebar-text">
                        Silahkan periksa ketersediaan villa pada kalender di samping. Tanggal yang tersedia 
                        dapat Anda pilih untuk melakukan pemesanan.
                    </p>
                    
                    <div class="calendar-legend">
                        <h3 class="legend-title">Keterangan</h3>
                        <div class="legend-item">
                            <div class="legend-color color-available"></div>
                            <span>Tersedia</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color color-selected"></div>
                            <span>Tanggal Dipilih</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color color-booked"></div>
                            <span>Sudah Dipesan</span>
                        </div>
                    </div>
                </div>
                
                <div class="calendar-main">
                    <div id="calendar"></div>
                    
                    <div class="booking-btn-container">
                        <a href="booking.php" class="booking-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3h-1V2h-2v1H8V2H6v1H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V9h14v10zM5 7V5h14v2H5zm2 4h10v2H7zm0 4h7v2H7z"/>
                            </svg>
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024. All Rights Reserved Villa Situ Lengkong Panjalu</p>
    </footer>

    <script src="js/navbar.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        $(function() {
            const bookedDates = <?php echo json_encode($booked_dates); ?>;
            
            // Initialize datepicker
            $("#calendar").datepicker({
                beforeShowDay: function(date) {
                    const string = $.datepicker.formatDate('yy-mm-dd', date);
                    
                    if (bookedDates.includes(string)) {
                        return [false, 'ui-state-booked-date', 'Tanggal sudah dipesan'];
                    }
                    return [true, '', ''];
                },
                dateFormat: 'yy-mm-dd',
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true,
                minDate: 0,
                numberOfMonths: 1
            });
            
            // Simple parallax effect for hero background
            $(window).scroll(function() {
                var scrollTop = $(window).scrollTop();
                var imgPos = scrollTop / 2.5;
                $('#parallax-hero').css('transform', 'translateY(' + imgPos + 'px)');
            });
        });
    </script>
</body>
</html>
