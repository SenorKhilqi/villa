<?php
require 'auth.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villa Panjalu</title>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        /* Reset dan font global */
        body {  
            font-family: Arial, sans-serif;  
            line-height: 1.6;  
            margin: 0;  
            padding: 0;  
            background-color: #f7f7f7; 
            size: 100%; 
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

        /* Gaya untuk gambar utama */
        .Gambar {
    width: 100%;
    overflow: hidden;
}

.Gambar img {
    width: 100% !important;
    height: auto; /* Menjaga rasio gambar tetap proporsional */
    display: block;
}

@media (max-width: 768px) {
    .Gambar {
        width: 100%;  /* Pastikan lebar gambar sesuai dengan ukuran layar */
        height: 300px;  /* Ukuran tinggi untuk layar lebih kecil */
    }

    .Gambar img {
        height: 100%;  /* Membuat gambar mengisi seluruh area di dalam .Gambar */
    }
}


        /* Header */
        header {
            text-align: center;
            padding: 20px;
        }
        .social-icons {
            display: flex;
            justify-content: center;
            gap: 50px;
        }
        .social-icon {
            width: 30px;
            height: auto;
        }

        /* Gaya untuk bagian villa */
        .villa {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            padding: 30px;
            background-color: #EBE3D5;
        }
        .villa img {
            width: 100%;
            max-width: 300px;
            height: auto;

        }
        .villa div {
            text-align: center;
            max-width: 400px;
            margin: 20px;
        }
        .villa h2 {
            font-size: 24px;
            margin: 0 0 8px;
        }
        .villa p {
            color: #555;
            margin: 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #776B5D;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .btn:hover {  
            transform: translateY(-5px); /* Efek mengambang saat hover */  
            background-color: #7a6a3a; /* Opsional: Ganti warna latar belakang saat hover */  
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #8d7b4f;
            color: white;
        }

        /* Media Query untuk layar kecil */
        @media (max-width: 600px) {
            .villa {
                flex-direction: column;
            }
            .villa img {
                margin-bottom: 20px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const menu = document.querySelector('.menu');

            menuToggle.addEventListener('click', function() {
                menu.classList.toggle('show');
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

    <div class="Gambar">
        <img src="kayu_hujung/IMG_46111.jpg" alt="Villa Image">
    </div>

    <header data-aos="zoom-in">
        <h1 >VILLA SITU LENGKONG</h1>
        <p>Temukan villa yang cocok untuk kebutuhan staycation Anda!</p>
        <div class="social-icons" data-aos="flip-left">
        <a href="https://www.instagram.com/"> <img class="social-icon instagram" src="logo/instagram.png" alt="Instagram"></a>
        <a href="https://www.tiktok.com/login"> <img class="social-icon tiktok" src="logo/tiktok.png" alt="TikTok"></a>
        <a href="https://wa.me/6289506892023" target="_blank"><img class="social-icon whatsapp" src="logo/whatsapp1.png" alt="WhatsApp"></a>
</div>
    </header>

    <div class="villa villa_hujung">
        <img src="kayu_hujung/IMG_4593.jpg" alt="Villa Kayu Hujung" data-aos="fade-right"
     data-aos-offset="300"
     data-aos-easing="ease-in-sine">
        <div>
            <h2 data-aos="fade-left">VILLA KAYU HUJUNG</h2>
            <p data-aos="fade-left">Kapasitas Maksimal 30 Orang</p>
            <a href="villa_kayu_hujung.php" class="btn" data-aos="fade-left">Lihat Detail</a>
        </div>
    </div>

    <div class="villa villa_dukuh">
        <img src="bata_dukuh/IMG_4725.jpg" alt="Villa Bata Dukuh" data-aos="fade-right"
     data-aos-offset="300"
     data-aos-easing="ease-in-sine">
        <div>
            <h2 data-aos="fade-left">VILLA BATA DUKUH</h2>
            <p data-aos="fade-left">Kapasitas Maksimal 30 Orang</p>
            <a href="villa_bata_dukuh.php" class="btn" data-aos="fade-left">Lihat Detail</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2024. All Rights Reserved Villa Panjalu</p>
    </footer>
</body>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</html>
