<?php
// Jika diperlukan, tambahkan logika PHP di sini.
?>
<?php
// Anda bisa menambahkan logika PHP di sini jika diperlukan, misalnya untuk dinamika konten.
?>

<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Villa Panjalu</title>  
    <style>
                /* Reset dan font global */
            body {  
            font-family: Arial, sans-serif;  
            line-height: 1.6;  
            margin: 0;  
            padding: 0;  
            background-color: #f7f7f7;  
        } 

        /* Gaya Navbar */
        .navbar {  
            display: flex;  
            justify-content: space-between;  
            align-items: center;  
            background-color: #B0A695; /* Light background color */  
            padding: 15px 30px;  
        }  

        .logo h1 {  
            font-size: 24px;  
            color: #4b3d33; /* Dark text color */  
            margin: 0;  
        }  

        .logo span {  
            font-style: italic;  
            color: #6b5b4a; /* Slightly different color for emphasis */  
        }  

        .menu {  
            list-style: none;  
            display: flex;  
            margin: 0;  
            padding: 0;  
        }  

        .menu li {  
            margin: 0 15px; /* Space between menu items */  
        }  

        .menu a {  
            text-decoration: none;  
            color: #4b3d33; /* Text color */  
            font-weight: 500; /* Semi-bold text */  
        }  

        .menu a:hover {  
            color: #6b5b4a; /* Change color on hover */  
            text-decoration: underline; /* Underline on hover */  
        }  

        .header {  
            background-color: #d0c4b1;  
            padding: 40px 20px;  
        }  

        .header-content {  
            display: flex;  
            align-items: center;  
            justify-content: center; /* Center align */  
            max-width: 1200px; /* Max width of content */  
            margin: 0 auto; /* Center align the content in the container */  
        }  

        .text-section {  
            flex: 1; /* Take up remaining space */  
            padding: 20px; /* Space around text */  
        }  

        .text-section h1,  
        .text-section h2 {  
            margin: 0; /* Remove default margin */  
        }  

        .header-image {  
            flex: 1; /* Take up half space */  
            max-width: 500px; /* Responsive image */  
            height: auto; /* Maintain aspect ratio */  
            border-radius: 10px; /* Optional: add rounded corners */  
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
                margin-top: 20px; /* Space above image */  
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
        }  

        .btn {  
            display: inline-block;  
            padding: 10px 15px;  
            background-color: #8d7b4f;  
            color: white;  
            text-decoration: none;  
            border-radius: 5px;  
            margin-top: 10px;  
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
            width: 100%;  
        }
    </style>
</head>  
<body>  
<nav class="navbar">  
        <div class="logo">  
            <h1>VILLA <span>Panjalu</span></h1>  
        </div>  
        <ul class="menu">  
            <li><a href="#">Beranda</a></li>  
            <li><a href="#">Villa Kami</a></li>  
            <li><a href="#">Cek Tanggal</a></li>  
            <li><a href="#">Tentang Kami</a></li>  
            <li><a href="#">Hubungi Kami</a></li>  
        </ul>  
    </nav>  
    <header class="header">  
        <div class="header-content">  
            <div class="text-section">  
                <h1>SELAMAT DATANG!</h1>  
                <h2>VILLA PANJALU</h2>  
                <p>Menemukan informasi villa di Panjalu untuk staycation lebih mudah dengan website kami.</p>  
            </div>  
            <img src="images/gunung.jpg" alt="Pemandangan Villa" class="header-image">  
        </div>  
    </header>   

    <section class="villa-details">  
        <h3>YUK LIHAT DETAIL VILLA!</h3>  
        <div class="villas">  
            <div class="villa">  
                <img src="images/gunung.jpg" alt="Villa Bata Dukuh">  
                <h4>VILLA BATA DUKUH</h4>  
                <a href="#" class="btn">LIHAT SELENGKAPNYA</a>  
            </div>  
            <div class="villa">  
                <img src="images/gunung.jpg" alt="Villa Kayu Hujung">  
                <h4>VILLA KAYU HUJUNG</h4>  
                <a href="#" class="btn">LIHAT SELENGKAPNYA</a>  
            </div>  
        </div>  
    </section>  

    <section class="proses">  
        <h3>PROSES PENYEWAAN VILLA</h3>  
        <ol>  
        <p><strong>PEMILIHAN VILLA DAN TANGGAL</strong><br>Tim kami akan membantu Anda menyesuaikan villa dan tanggal yang tersedia. Sesuaikan dengan kebutuhan dan tujuan Anda menginap di villa kami.</p>  
        <p><strong>PROSES DP</strong><br>Saat Anda telah menemukan villa dengan tanggal yang pas, maka tahap selanjutnya adalah pembayaran DP minimal sebesar 50% dari harga sewa villa.</p>
        <p><strong>PELUNASAN</strong><br>Anda diharapkan untuk melunasi pembayaran sewa villa maksimal tiga hari sebelum tanggal menginap Anda. Agar pada saat check-in, Anda tidak perlu mengulangi pembayaran lagi.</p>  
        </ol>  
    </section>  

    <footer>  
        <p>&copy; 2024. All Rights Reserved Villa Panjalu</p>  
    </footer>  
</body>  
</html>


