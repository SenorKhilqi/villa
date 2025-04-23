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
            padding: 10px 20px;  
        }  
        .logo img {  
            max-width: 70px;  
            height: auto;  
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
            font-weight: 600;  
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
        header {
    display: flex;
    flex-direction: column; /* Mengatur elemen dalam kolom */
    align-items: center; /* Mengatur elemen ke tengah secara horizontal */
    justify-content: center; 
}

header h1 {
    color: #554C42; /* Mengubah warna h1 */
    font-size: 55px;
    margin-bottom: 10px;
    margin-top: 0px; /* Mengubah ukuran font h1 */
}

header h2 {
    color: #554C42; 
    font-size: 35px;
    margin-top: 10px; /* Ubah margin-top jika perlu untuk lebih dekat dengan h1 */
}

        @media (max-width: 480px) {
            h1 {
                font-size: 1.5em; /* Ukuran font lebih kecil untuk perangkat mobile */
                margin-top: 15px; /* Mengurangi margin atas */
                margin-bottom: 20px; /* Mengurangi margin bawah */
            }
        }

        h2 {
            color: #554C42; /* Mengubah warna h2 */
            font-size: 30px;
            margin-top: 20px;
            margin-bottom: 0px;/* Mengubah ukuran font h2 */
        }

        /* Gaya untuk gambar villa */  
        .villa {    
            padding: 20px;  
            background-color: #fff;     
        }  
        .villa img {  
            max-width: 80%;  
            height: auto;  
            border-radius: 10px;   
            margin: 0 auto; /* Center the image */  
            display: block; 
            margin-bottom: 100px;/* Make it a block element */  
        } 

        .villa p {  
            color: #555; 
            font-size: 25px; 
            margin: 20px 95px 0 95px;

        }  

        @media (max-width: 480px) {
            .villa p{
                font-size: 15px; 
            }}

        .detailvilla h1 {  
            font-size: 50px;
            margin-top: 20px; 
            margin-bottom: 30px; 
            text-align: center;
            color: #554C42;
        }  

        @media (max-width: 480px) {
            .detailvilla h1 {
                font-size: 1.5em; /* Ukuran font lebih kecil untuk perangkat mobile */
                margin-top: 15px; /* Mengurangi margin atas */
                margin-bottom: 20px; /* Mengurangi margin bawah */
            }
        }


        .scroll-container {
            width: 100%; /* Lebar kontainer */
            overflow-x: auto; /* Mengaktifkan scroll horizontal */
            white-space: nowrap; /* Mencegah gambar terputus ke baris baru */
        }
        .scroll-container::-webkit-scrollbar{
            display: none;
        }
        .scroll-container img {
            width: 25%;
            height:auto; /* Atur lebar gambar sesuai kebutuhan */
            margin-left: 30px; /* Jarak antar gambar */
        }

        @media (max-width: 480px) {
    .scroll-container img {
        width: 30%; /* Mengubah lebar gambar menjadi 80% untuk perangkat mobile */
        margin-left: 8px; /* Mengurangi margin kiri untuk tampilan lebih baik */
        margin-right: 8px; /* Menambahkan margin kanan untuk tampilan lebih baik */
    }
}
        .harga {
            font-size: 30px;
            margin-top: 20px; 
            margin-bottom: 30px; 
            text-align: center;
            color: #554C42;
            font-weight: bold;
        }
        /* Footer */
        .details {  
            padding-top: 20px; 
            padding-bottom: 40px;
            margin-top: 20px;  
            padding-left: 30px;
            background-color: #EBE3D5;
        }  

        .details p,ul{
            margin-left: 40px;
            font-family: Arial, Helvetica, sans-serif;
        }
        
        @media (max-width: 768px) {
            .details {
                padding-left: 15px; /* Mengurangi padding untuk perangkat lebih kecil */
                padding-top: 15px; /* Mengurangi padding atas */
                padding-bottom: 30px; /* Mengurangi padding bawah */
            }

            .details p, ul {
                margin-left: 0; /* Menghilangkan margin kiri untuk tampilan lebih baik */
                font-size: 14px; /* Mengurangi ukuran font untuk perangkat kecil */
            }
        }

        .button {
            display: flex; /* Menggunakan flexbox */
            justify-content: center; /* Menyusun tombol secara horizontal ke tengah */
            align-items: center; /* Menyusun tombol secara vertikal ke tengah jika diperlukan */
            flex-wrap: wrap; /* Membungkus tombol jika diperlukan */
            margin-top: 30px;  
            margin-bottom: 40px;
        }

        .btn {  
            display: inline-block;  
            padding: 10px 20px;  
            background-color: #776B5D;  
            color: #fff;  
            text-decoration: none;  
            border-radius: 30px;  
            margin-top: 10px;  
            margin-bottom: 20px;
            transition: background-color 0.3s; 
            margin-left: 30px; 
            font-weight: bold;
        }

        .bn {  
            display: inline-block;  
            padding: 10px 20px;  
            background-color: #EBE3D5;  
            color: #554C42;  
            text-decoration: none;  
            border-radius: 30px;  
            margin-top: 10px;  
            margin-bottom: 20px;
            transition: background-color 0.3s; 
            margin-left: 30px; 
            font-weight: bold;
        }

        .btn:hover {  
            transform: translateY(-5px); /* Efek mengambang saat hover */  
            background-color: #EBE3D5; /* Opsional: Ganti warna latar belakang saat hover */  
        }.bn:hover {  
            transform: translateY(-5px); /* Efek mengambang saat hover */  
            background-color: #B0A695; /* Opsional: Ganti warna latar belakang saat hover */  
        }

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

    <header>  
        <h2>VILLA</h2>
        <h1>BATA DUKUH</h1>  
    </header>  

    <div class="villa">  
    <img src="bata_dukuh/IMG_4697.jpg" alt="Villa Kayu Hujung">  
        <p>Villa Kayu Hujung merupakan villa dengan fasilitas yang lumayan lengkap, Villa ini juga memiliki 
pemandangan indah Danau Situ Lengkong, terdapat area permainan billiard, dan gathering area 
baik outdoor maupun indoor. Villa Kayu Hujung merupakan pilihan villa yang tepat untuk dijadikan
destinasi staycation Anda.</p>
        <p>Villa Kayu Hujung terletak tidak jauh dari destinasi wisata curug tujuh. Anda dapat mengunjungi 
area tersebut tanpa harus jauh-jauh ke lokasi lain. Rencanakan staycation Anda bersama 
Villa Situ Lengkong di Villa Kayu Hujung.</p>

    </div>
    <div class="detailvilla">
        <h1>DETAIL VILLA</h1>
    </div>
    <div class="scroll-container">
            <img src="bata_dukuh/IMG_4713.jpg" alt="Gambar 1">
            <img src="bata_dukuh/IMG_4717.jpg" alt="Gambar 2">
            <img src="bata_dukuh/IMG_4715.jpg" alt="Gambar 3">
            <img src="bata_dukuh/IMG_4720.jpg" alt="Gambar 4">
            <img src="bata_dukuh/IMG_4725.jpg" alt="Gambar 5">
            <img src="bata_dukuh/IMG_4697.jpg" alt="Gambar 6">
            <img src="bata_dukuh/IMG_4702.jpg" alt="Gambar 7">
            <img src="bata_dukuh/IMG_4708.jpg" alt="Gambar 8">
    </div> 
    <div class="harga">
        <p>Harga: Rp. 2.000.000/24 Jam</p>  
    </div>
    <div class="details">  
            <p>Kapasitas: Maksimal 30 Orang</p>  
            <p>Fasilitas:</p>  
            <ul>  
                <li>Kolam Renang</li>  
                <li>Area Parkir</li>  
                <li>Dapur</li>  
                <li>Ruang Meeting</li>  
                <li>Area Permainan Billiard</li>  
                <li>Smart TV</li>  
            </ul>  
    </div>  
    <div>
        <div class="button">
        <a href="calender.php" class="btn">Cek Tanggal</a>  
        <a href="booking.php" class="bn">Booking Villa</a>  
    </div>
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