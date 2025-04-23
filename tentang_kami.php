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
            background-color: #ffffff;  
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

        /* Header */
        /* Styling Umum */
        .header {
            padding: 20px;
            background-color: #ffffff;
            font-family: Arial, sans-serif;
        }

        /* Styling untuk teks H1 */
        .text-section h1 {
            text-align: center;
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
        }

        /* Styling untuk gambar dan teks H2 */
        .image-and-description {
            display: flex; /* Membuat elemen di dalamnya berbaris horizontal */
            align-items: center; /* Gambar dan teks sejajar secara vertikal */
            justify-content: center; /* Pusatkan secara horizontal */
            gap: 30px; /* Memberikan jarak antar elemen */
            max-width: 1200px; /* Batasi lebar keseluruhan */
            margin: 0 auto; /* Pusatkan kontainer */
        }

        /* Styling untuk gambar */
        .header-image {
            max-width: 30%; /* Ukuran gambar */
            height: auto;
            margin: 20px 100px 20px auto;
            border-radius: 8px; /* Tambahkan sudut tumpul */
        }

        /* Styling untuk teks H2 */
        .penjelasan h2 {
            font-size: 18px;
            line-height: 1.6;
            color: #555;
            margin: 0;
        }

        /* Responsif untuk layar kecil */
        @media (max-width: 768px) {
            .image-and-description {
                flex-direction: column; /* Ubah ke tata letak vertikal untuk layar kecil */
                text-align: center;
            }

            .header-image {
                max-width: 100%; /* Ukuran gambar */
                height: auto;
                border-radius: 8px; /* Tambahkan sudut tumpul */
            }

            .penjelasan h2 {
                padding: 10px;
            }
        }

        .maps{
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            background-color: #ffffff; /* Warna background (opsional) */
        }

        .narahubung {
            text-align: center; 
            padding: 10px;  
            background-color: #ffffff;  
            margin: 0;
            color: #554C42;
        }

        /* Footer */
        .footer {  
            text-align: center;  
            padding: 20px;  
            background-color: #8d7b4f;  
            color: white;  
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
    <div class="header">
        <!-- Teks Selamat Datang -->
        <div class="text-section">
            <h1>TENTANG KAMI</h1>
        </div>

        <!-- Gambar dan Penjelasan -->
        <div class="image-and-description">
            <img src="kayu_hujung/IMG_46111.jpg" alt="Pemandangan Villa" class="header-image" data-aos="fade-right">
            <div class="penjelasan" data-aos="fade-left">
                <h2>
                    Kami menyediakan beberapa Private Villa yang dapat Anda sewa harian. Lengkap dengan informasi foto, harga, lokasi dan juga fasilitas yang tersedia. Anda juga dapat melakukan reservasi online melalui website kami “Villa Situ Lengkong”.
                </h2>
            </div>
        </div>
    </div>

    <div class="maps">
        <p>LOKASI</p>

    </div>
<!-- Google Maps Embed -->
<div class="maps">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d489.7354575173409!2d108.2679508238487!3d-7.131894150463401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f471dd336c941%3A0x43f70d6ebc4a936c!2sSITU%20LENGKONG%20PANJALU!5e0!3m2!1sen!2sid!4v1732207028254!5m2!1sen!2sid" 
        width="600" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<div class="narahubung">
    <h2>Hubungi Kami</h2>
    <h3>0895-0689-2023 / 0821-30133-0892</h3>
    <h3>villasitulengkong@gmail.com</h3>
</div>

    <footer class="footer">  
        <p>&copy; 2024. All Rights Reserved Villa Panjalu</p>  
    </footer>  
</body>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</html>
