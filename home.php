<?php
require 'auth.php'; 
?>
<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Villa Panjalu</title> 
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.querySelector('.menu-toggle');
            const menu = document.querySelector('.menu');
            
            toggleButton.addEventListener('click', function() {
                menu.classList.toggle('show');
            });
        });
    </script> 

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
                display: none; /* Hide toggle button by default */
                font-size: 24px;
                background: none;
                border: none;
                cursor: pointer;
            }

            @media (max-width: 768px) {
                .menu {
                    display: none; /* Hide menu by default on small screens */
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
                    display: block; /* Show toggle button on small screens */
                }

                .navbar {
                    position: relative;
                }
            }

            /* Show menu when toggle button is clicked */
            .menu.show {
                display: flex;
            } 

        .header {  
            background-color: #ffffff;  
            padding-top: 20px;
            padding-bottom: 20px;
        }  

        @media(max-widht:768px) {
            .header {
                padding-top: 0px;
                padding-bottom: 0px;
            }
        }

        .header-content {  
            display: flex;  
            align-items: center;  
            justify-content: center; /* Center align */  
            max-width: 1200px; /* Max width of content */  
            margin: 0 auto;
            flex-wrap: wrap;
            padding:0 20px; /* Center align the content in the container */  
        }  

        .text-section {  
            flex: 1; /* Take up remaining space */  
            padding: 20px;
            text-align: left; /* Space around text */  
        }  

        .text-section h1 {
            font-size: 36px;
            
        }  
        .text-section h2  {  
            margin: 0; /* Remove default margin */ 
        }  

        .text-color{
            color: #4a4a4a;
        }

        .header-image {  
            flex: 1; /* Take up half space */  
            max-width: 35%; /* Responsive image */  
            height: auto; /* Maintain aspect ratio */  
            border-radius: 5px; /* Optional: add rounded corners */  
        }

        @media (max-width: 768px) {  
            .header-content {  
                flex-direction: column; /* Stack on smaller screens */  
                align-items: center; /* Center align */  
            }  

            .text-section,  
            .header-image {  
                flex: none; /* Reset flex for stacking */  
                max-width: 100%; /* Full width on small screens */  
            }  

            .header-image {  
                margin-top: 20px; 
                margin-left: 10px;
                margin-right: 10px;/* Space above image */  
            }  
        }  

        .villa-details {  
            text-align: center;  
            padding: 30px 0;  
            background-color: #EBE3D5;
        }  

        .villas {  
            display: flex;  
            justify-content: center;  
            margin: 20px 0;  
            gap: 20px;  
        }  

        .villa {  
            max-width: 300px;  
            text-align: center;  
        }  

        .villa img {  
            width: 100%;  
            height: auto;  
            border-radius: 10px;  
            transition: transform 0.3s ease; /* Tambahkan transisi */  
        }  

        .villa img:hover {  
            transform: scale(1.05); /* Efek memperbesar saat hover */  
        }

        .btn {  
            display: inline-block;  
            padding: 10px 15px;  
            background-color: #8d7b4f;  
            color: white;  
            text-decoration: none;  
            border-radius: 5px;  
            margin-top: 10px;  
            transition: transform 0.3s ease; /* Tambahkan transisi */  
        }  

        .btn:hover {  
            transform: translateY(-5px); /* Efek mengambang saat hover */  
            background-color: #7a6a3a; /* Opsional: Ganti warna latar belakang saat hover */  
        }

        .proses {  
            padding: 30px;  
            background-color: #ffffff;  
            text-align: center;  
        }  

        .proses h3 {  
            margin-bottom: 20px;  
        }  

        footer {  
            text-align: center;  
            padding: 20px;  
            background-color: #8d7b4f;  
            color: white;  
            position: relative;  
            bottom: 0;   
        }

        .container {  
            max-width: 1200px;  
            margin: auto; 
            padding-bottom: 30px; 
        }  

        h1 {  
            text-align: center;  
            color: #4a4a4a;  
        }  

        .process {  
            display: flex;  
            justify-content: space-between;  
            flex-wrap: wrap;  
            margin-top: 20px;  
        }  

        .step {  
            background-color: #fff;  
            border: 1px solid #e0e0e0;  
            border-radius: 8px;  
            padding: 20px;  
            margin: 10px;  
            flex: 1;  
            min-width: 250px; /* Membatasi lebar minimum kolom */  
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);  
        }  

        .step h2 {  
            color: #4a4a4a;  
            font-size: 1.2em;  
        }  

        .step p {  
            color: #606060;  
            line-height: 1.5;  
        }
    </style>
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
<header class="header">  
    <div class="header-content text-color">  
        <div class="text-section" data-aos="fade-up">  
            <h1>SELAMAT DATANG!</h1>  
            <h2>VILLA SITU LENGKONG</h2>  
            <p>Menemukan informasi villa di Panjalu untuk staycation lebih mudah dengan website kami.</p>  
        </div>  
        <img src="kayu_hujung/IMG_46111.jpg" alt="Pemandangan Villa" class="header-image" data-aos="zoom-in">  
    </div>  
</header>   

<section>  
    <div class="villa-details text-color" data-aos="fade-up">
    <h3>YUK LIHAT DETAIL VILLA!</h3>  
    <div class="villas text-color" data-aos="fade-up">  
        <div class="villa">  
            <img src="kayu_hujung/IMG_4589.jpg" alt="Villa Bata Dukuh">  
            <h4>VILLA BATA DUKUH</h4>   
        </div>  
        <div class="villa">  
            <img src="bata_dukuh/IMG_4697.jpg" alt="Villa Kayu Hujung">  
            <h4>VILLA KAYU HUJUNG</h4>   
        </div>  
    </div>  
    <a href="villa_kami.php" class="btn" data-aos="fade-up">LIHAT SELENGKAPNYA</a> 
    </div>
</section>  

<div class="container" data-aos="fade-up">  
    <h1>PROSES PENYEWAAN VILLA</h1>  
    <div class="process" >  
        <div class="step" data-aos="fade-right">  
            <h2>1. PEMILIHAN VILLA DAN TANGGAL</h2>  
            <p>Tim kami akan membantu Anda menyesuaikan villa dan tanggal yang tersedia. Sesuaikan dengan tim kami mengenai kebutuhan dan tujuan Anda menginap di villa kami.</p>  
        </div>  
        <div class="step" data-aos="fade-zoom-in">  
            <h2>2. PROSES DP</h2>  
            <p>Saat Anda telah menemukan villa dengan tanggal yang pas, maka tahap selanjutnya adalah pembayaran DP minimal sebesar 50% dari harga sewa villa.</p>  
        </div>  
        <div class="step" data-aos="fade-left">  
            <h2>3. PELUNASAN</h2>  
            <p>Anda diharapkan untuk melunasi pembayaran sewa villa maksimal tiga hari sebelum tanggal menginap Anda. Agar pada saat Anda check-in, Anda tidak perlu memikirkan mengenai pembayaran lagi.</p>  
        </div>  
    </div>  
</div>  

<footer >  
    <p>&copy; 2024. All Rights Reserved Villa Panjalu</p>  
</footer>  
</body>
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</html>