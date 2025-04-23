<?php
require 'auth.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Panjalu - Villa Kami</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        /* Banner styling */
        .banner {
            width: 100%;
            height: 500px;
            overflow: hidden;
            position: relative;
        }

        .banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: white;
            text-align: center;
        }

        .banner-overlay h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .banner-overlay p {
            font-size: 20px;
            max-width: 800px;
            padding: 0 20px;
        }

        /* Header styling */
        header {
            text-align: center;
            padding: 60px 0;
            background-color: #fff;
        }

        header h1 {
            font-size: 42px;
            margin-bottom: 20px;
            color: #554C42;
        }

        header p {
            font-size: 18px;
            color: #666;
            max-width: 800px;
            margin: 0 auto 30px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 30px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            transition: transform 0.3s ease;
        }

        .social-icon:hover {
            transform: scale(1.2) rotate(10deg);
        }

        /* Villa card styling */
        .villa-section {
            padding: 80px 0;
            background-color: #EBE3D5;
        }

        .villa-card {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .villa-card:last-child {
            margin-bottom: 0;
        }

        .villa-card img {
            width: 400px;
            height: 300px;
            object-fit: cover;
        }

        .villa-card-content {
            padding: 30px;
            flex: 1;
        }

        .villa-card h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #554C42;
        }

        .villa-card p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .villa-capacity {
            font-style: italic;
            color: #776B5D;
            margin-bottom: 20px;
        }

        /* Media queries */
        @media (max-width: 768px) {
            .banner {
                height: 300px;
            }

            .banner-overlay h1 {
                font-size: 32px;
            }

            .banner-overlay p {
                font-size: 16px;
            }

            .villa-card {
                flex-direction: column;
            }

            .villa-card img {
                width: 100%;
                height: 200px;
            }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="banner">
    <img src="kayu_hujung/IMG_46111.jpg" alt="Villa Banner">
    <div class="banner-overlay">
        <h1>VILLA SITU LENGKONG</h1>
        <p>Temukan villa yang cocok untuk kebutuhan staycation Anda!</p>
    </div>
</div>

<header>
    <div class="container">
        <h1 data-aos="fade-up">TEMUKAN VILLA IDEAL ANDA</h1>
        <p data-aos="fade-up" data-aos-delay="200">Nikmati pengalaman menginap yang tak terlupakan di villa-villa pilihan kami dengan pemandangan indah Danau Situ Lengkong dan fasilitas lengkap.</p>
        <div class="social-icons" data-aos="fade-up" data-aos-delay="400">
            <a href="https://www.instagram.com/" target="_blank"> 
                <img class="social-icon instagram" src="logo/instagram.png" alt="Instagram">
            </a>
            <a href="https://www.tiktok.com/login" target="_blank"> 
                <img class="social-icon tiktok" src="logo/tiktok.png" alt="TikTok">
            </a>
            <a href="https://wa.me/6289506892023" target="_blank">
                <img class="social-icon whatsapp" src="logo/whatsapp1.png" alt="WhatsApp">
            </a>
        </div>
    </div>
</header>

<section class="villa-section">
    <div class="container">
        <div class="villa-card" data-aos="fade-up">
            <img src="kayu_hujung/IMG_4593.jpg" alt="Villa Kayu Hujung">
            <div class="villa-card-content">
                <h2>VILLA KAYU HUJUNG</h2>
                <p class="villa-capacity">Kapasitas Maksimal 30 Orang</p>
                <p>Villa dengan fasilitas lengkap dan pemandangan indah Danau Situ Lengkong. Dilengkapi dengan area permainan billiard dan ruang pertemuan indoor maupun outdoor.</p>
                <a href="villa_kayu_hujung.php" class="btn">Lihat Detail</a>
            </div>
        </div>

        <div class="villa-card" data-aos="fade-up" data-aos-delay="200">
            <img src="bata_dukuh/IMG_4725.jpg" alt="Villa Bata Dukuh">
            <div class="villa-card-content">
                <h2>VILLA BATA DUKUH</h2>
                <p class="villa-capacity">Kapasitas Maksimal 30 Orang</p>
                <p>Villa nyaman dengan pemandangan indah dan fasilitas lengkap. Tempat ideal untuk keluarga atau acara gathering bersama teman-teman dekat.</p>
                <a href="villa_bata_dukuh.php" class="btn">Lihat Detail</a>
            </div>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2024. All Rights Reserved Villa Situ Lengkong Panjalu</p>
    </div>
</footer>

<script src="js/navbar.js"></script>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease',
        once: true
    });
</script>
</body>
</html>
