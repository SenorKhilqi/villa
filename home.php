<?php
require 'auth.php'; 
?>
<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Villa Panjalu</title> 
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>
        .header {  
            background-color: #ffffff;  
            padding: 80px 0 60px;
        }  

        .header-content {  
            display: flex;  
            align-items: center;  
            gap: 40px;
            flex-wrap: wrap;
        }  

        .text-section {  
            flex: 1;
            min-width: 300px;
        }  

        .text-section h1 {
            font-size: 48px;
            margin-bottom: 10px;
            color: #554C42;
        }  
        
        .text-section h2 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #776B5D;
        }
        
        .text-section p {
            font-size: 18px;
            margin-bottom: 30px;
            color: #666;
        }

        .header-image {  
            flex: 1;
            min-width: 300px;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.5s ease;
        }

        .header-image:hover {
            transform: scale(1.03);
        }

        .villa-details {  
            text-align: center;  
            padding: 80px 0;  
            background-color: #EBE3D5;
        }  

        .villas {  
            display: flex;  
            justify-content: center;  
            flex-wrap: wrap;
            gap: 30px;
            margin: 50px 0;
        }  

        .villa {  
            max-width: 350px;
            transition: transform 0.3s ease;
        }  

        .villa img {  
            width: 100%;  
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }  

        .villa img:hover {  
            transform: scale(1.05);
        }
        
        .villa h4 {
            margin: 15px 0;
            font-size: 20px;
            color: #554C42;
        }

        .process-section {
            padding: 80px 0;
            background-color: #ffffff;
        }

        .process {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-top: 50px;
        }

        .step {
            flex: 1;
            min-width: 300px;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .step:hover {
            transform: translateY(-10px);
        }

        .step h2 {
            color: #776B5D;
            font-size: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #B0A695;
            color: white;
            border-radius: 50%;
            margin-right: 15px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .header {
                padding: 60px 0 40px;
            }
            
            .text-section h1 {
                font-size: 36px;
            }
            
            .text-section h2 {
                font-size: 24px;
            }
            
            .villas {
                flex-direction: column;
                align-items: center;
            }
            
            .villa {
                max-width: 100%;
            }
        }
    </style>
</head>  
<body>  
<?php include 'navbar.php'; ?>

<header class="header">  
    <div class="container">
        <div class="header-content">  
            <div class="text-section" data-aos="fade-right" data-aos-duration="1000">  
                <h1>SELAMAT DATANG!</h1>  
                <h2>VILLA SITU LENGKONG</h2>  
                <p>Temukan pengalaman staycation sempurna di Panjalu dengan villa-villa pilihan kami. Pemandangan indah, fasilitas lengkap, dan kenyamanan tak terlupakan.</p>
                <a href="villa_kami.php" class="btn">Lihat Villa Kami</a>
            </div>  
            <img src="kayu_hujung/IMG_46111.jpg" alt="Pemandangan Villa" class="header-image" data-aos="fade-left" data-aos-duration="1000">  
        </div>
    </div>  
</header>   

<section class="villa-details">
    <div class="container">
        <h3 class="section-title" data-aos="fade-up">VILLA PILIHAN KAMI</h3>  
        <div class="villas" data-aos="fade-up" data-aos-delay="200">  
            <div class="villa" data-aos="zoom-in" data-aos-delay="300">  
                <img src="kayu_hujung/IMG_4589.jpg" alt="Villa Bata Dukuh">  
                <h4>VILLA BATA DUKUH</h4>   
            </div>  
            <div class="villa" data-aos="zoom-in" data-aos-delay="500">  
                <img src="bata_dukuh/IMG_4697.jpg" alt="Villa Kayu Hujung">  
                <h4>VILLA KAYU HUJUNG</h4>   
            </div>  
        </div>  
        <a href="villa_kami.php" class="btn" data-aos="fade-up" data-aos-delay="700">LIHAT SELENGKAPNYA</a>
    </div> 
</section>  

<section class="process-section">
    <div class="container">
        <h1 class="section-title" data-aos="fade-up">PROSES PENYEWAAN VILLA</h1>  
        <div class="process">  
            <div class="step" data-aos="fade-right" data-aos-delay="200">  
                <h2><span class="step-number">1</span>PEMILIHAN VILLA DAN TANGGAL</h2>  
                <p>Tim kami akan membantu Anda menyesuaikan villa dan tanggal yang tersedia. Sesuaikan dengan tim kami mengenai kebutuhan dan tujuan Anda menginap di villa kami.</p>  
            </div>  
            <div class="step" data-aos="fade-up" data-aos-delay="400">  
                <h2><span class="step-number">2</span>PROSES DP</h2>  
                <p>Saat Anda telah menemukan villa dengan tanggal yang pas, maka tahap selanjutnya adalah pembayaran DP minimal sebesar 50% dari harga sewa villa.</p>  
            </div>  
            <div class="step" data-aos="fade-left" data-aos-delay="600">  
                <h2><span class="step-number">3</span>PELUNASAN</h2>  
                <p>Anda diharapkan untuk melunasi pembayaran sewa villa maksimal tiga hari sebelum tanggal menginap Anda. Agar pada saat Anda check-in, Anda tidak perlu memikirkan mengenai pembayaran lagi.</p>  
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