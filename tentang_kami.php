<?php
require 'auth.php'; 
?>
<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Villa Panjalu - Tentang Kami</title>  
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>  
        /* Hero section */
        .hero {
            background-color: #EBE3D5;
            padding: 120px 0 80px;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 48px;
            color: #554C42;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }
        
        .hero h1::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 3px;
            background-color: #B0A695;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        /* About section */
        .about-section {
            padding: 80px 0;
            background-color: #fff;
        }

        .about-container {
            display: flex;
            align-items: center;
            gap: 50px;
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .about-image {
            flex: 1;
            min-width: 300px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        
        .about-image img:hover {
            transform: scale(1.05);
        }

        .about-content {
            flex: 1;
            min-width: 300px;
        }
        
        .about-content h2 {
            font-size: 32px;
            color: #554C42;
            margin-bottom: 20px;
        }
        
        .about-content p {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }

        /* Location section */
        .location-section {
            padding: 80px 0;
            background-color: #f9f9f9;
        }
        
        .location-title {
            text-align: center;
            font-size: 36px;
            color: #554C42;
            margin-bottom: 40px;
            position: relative;
        }
        
        .location-title::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 3px;
            background-color: #B0A695;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }

        .map-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .map-container iframe {
            width: 100%;
            height: 450px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Contact section */
        .contact-section {
            padding: 80px 0;
            background-color: #EBE3D5;
            text-align: center;
        }
        
        .contact-title {
            font-size: 36px;
            color: #554C42;
            margin-bottom: 15px;
        }
        
        .contact-info {
            font-size: 20px;
            color: #776B5D;
            margin-bottom: 10px;
        }
        
        .contact-email {
            font-size: 18px;
            color: #776B5D;
            margin-bottom: 30px;
        }
        
        .contact-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .contact-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background-color: #776B5D;
            color: white;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .contact-btn:hover {
            transform: translateY(-5px);
            background-color: #554C42;
        }
        
        .contact-btn img {
            width: 24px;
            height: 24px;
        }

        /* Email icon fallback */
        .email-icon {
            width: 24px;
            height: 24px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='white' viewBox='0 0 24 24'%3E%3Cpath d='M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            display: inline-block;
        }

        /* Media queries */
        @media (max-width: 768px) {
            .hero {
                padding: 80px 0 60px;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .about-container {
                flex-direction: column;
            }
            
            .location-title {
                font-size: 28px;
            }
            
            .map-container iframe {
                height: 350px;
            }
            
            .contact-title {
                font-size: 28px;
            }
            
            .contact-info, .contact-email {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>  
<?php include 'navbar.php'; ?>

<section class="hero">
    <div class="container">
        <h1 data-aos="fade-up">TENTANG KAMI</h1>
    </div>
</section>

<section class="about-section">
    <div class="about-container">
        <div class="about-image" data-aos="fade-right">
            <img src="kayu_hujung/IMG_46111.jpg" alt="Pemandangan Villa">
        </div>
        <div class="about-content" data-aos="fade-left">
            <h2>Villa Situ Lengkong Panjalu</h2>
            <p>
                Kami menyediakan beberapa Private Villa yang dapat Anda sewa harian. Lengkap dengan informasi foto, harga, lokasi dan juga fasilitas yang tersedia.
            </p>
            <p>
                Villa Situ Lengkong menawarkan pengalaman menginap yang tak terlupakan dengan pemandangan indah Danau Situ Lengkong. Setiap villa kami dilengkapi dengan fasilitas modern dan nyaman, cocok untuk liburan keluarga, gathering dengan teman, atau acara spesial lainnya.
            </p>
            <p>
                Anda juga dapat melakukan reservasi online melalui website kami "Villa Situ Lengkong" dengan mudah dan cepat.
            </p>
            <a href="villa_kami.php" class="btn">Lihat Villa Kami</a>
        </div>
    </div>
</section>

<section class="location-section">
    <div class="container">
        <h2 class="location-title" data-aos="fade-up">LOKASI KAMI</h2>
        <div class="map-container" data-aos="zoom-in">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d489.7354575173409!2d108.2679508238487!3d-7.131894150463401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f471dd336c941%3A0x43f70d6ebc4a936c!2sSITU%20LENGKONG%20PANJALU!5e0!3m2!1sen!2sid!4v1732207028254!5m2!1sen!2sid" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

<section class="contact-section">
    <div class="container" data-aos="fade-up">
        <h2 class="contact-title">Hubungi Kami</h2>
        <p class="contact-info">0895-0689-2023 / 0821-3013-0892</p>
        <p class="contact-email">villasitulengkong@gmail.com</p>
        
        <div class="contact-buttons">
            <a href="https://wa.me/6289506892023" target="_blank" class="contact-btn">
                <img src="logo/whatsapp1.png" alt="WhatsApp">
                WhatsApp
            </a>
            <a href="mailto:villasitulengkong@gmail.com" class="contact-btn">
                <span class="email-icon"></span>
                Email
            </a>
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
