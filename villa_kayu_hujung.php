<?php
require 'auth.php'; 
?>
<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Villa Kayu Hujung - Villa Panjalu</title>  
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <style>  
        :root {
            --primary: #B0A695;
            --primary-dark: #8A7F6C;
            --primary-light: #EBE3D5;
            --accent: #776B5D;
            --text-dark: #333333;
            --text-light: #666666;
            --white: #ffffff;
            --gray-light: #f7f7f7;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {  
            font-family: 'Poppins', sans-serif;  
            line-height: 1.6;  
            margin: 0;  
            padding: 0;  
            background-color: var(--white);
            color: var(--text-dark);
        }  

        /* Hero section */
        .villa-hero {
            position: relative;
            height: 70vh;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('kayu_hujung/IMG_4593.jpg');
            background-size: cover;
            background-position: center;
            z-index: -1;
            filter: brightness(0.7);
        }
        
        .hero-content {
            text-align: center;
            color: var(--white);
            z-index: 1;
            padding: 0 20px;
        }
        
        .hero-subtitle {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 300;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 60px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* Villa description section */
        .villa-description {
            padding: 80px 0;
            background-color: var(--white);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .description-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .description-content p {
            font-size: 18px;
            line-height: 1.8;
            color: var(--text-light);
            margin-bottom: 30px;
        }
        
        /* Gallery section */
        .villa-gallery {
            padding: 60px 0;
            background-color: var(--gray-light);
        }
        
        .section-title {
            text-align: center;
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 50px;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            width: 80px;
            height: 3px;
            background-color: var(--primary);
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .gallery-container {
            position: relative;
        }
        
        .gallery-scroll {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
            padding: 20px 0;
            scroll-behavior: smooth;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }
        
        .gallery-scroll::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        
        .gallery-scroll img {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .gallery-scroll img:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: var(--white);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            z-index: 1;
            font-size: 20px;
            transition: all 0.3s ease;
            color: var(--accent);
        }
        
        .gallery-nav:hover {
            background-color: var(--primary);
            color: var(--white);
        }
        
        .gallery-prev {
            left: 10px;
        }
        
        .gallery-next {
            right: 10px;
        }
        
        /* Details section */
        .villa-details {
            padding: 80px 0;
            background-color: var(--white);
        }
        
        .details-container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }
        
        .details-left {
            flex: 1;
            min-width: 300px;
        }
        
        .price-box {
            background-color: var(--primary-light);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .price-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .price-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 20px;
        }
        
        .price-period {
            font-size: 16px;
            color: var(--text-light);
        }
        
        .details-info {
            background-color: var(--gray-light);
            padding: 30px;
            border-radius: 15px;
        }
        
        .details-info h3 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--accent);
        }
        
        .details-info p {
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .facilities-list {
            list-style-type: none;
            padding-left: 0;
        }
        
        .facilities-list li {
            padding: 10px 0;
            font-size: 16px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
        }
        
        .facilities-list li::before {
            content: '✓';
            color: var(--accent);
            margin-right: 10px;
            font-weight: bold;
        }
        
        .details-right {
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .action-btn {
            display: block;
            padding: 16px 20px;
            width: 100%;
            text-align: center;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--accent);
            color: var(--white);
        }
        
        .btn-secondary {
            background-color: var(--primary-light);
            color: var(--accent);
        }
        
        .action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        /* Footer */
        .footer {  
            text-align: center;  
            padding: 30px 20px;  
            background-color: #8d7b4f;  
            color: var(--white);  
        }  
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 40px;
            }
            
            .hero-subtitle {
                font-size: 20px;
            }
            
            .villa-description, .villa-gallery, .villa-details {
                padding: 50px 0;
            }
            
            .description-content p {
                font-size: 16px;
            }
            
            .gallery-nav {
                width: 40px;
                height: 40px;
            }
            
            .section-title {
                font-size: 30px;
            }
            
            .details-container {
                flex-direction: column;
            }
            
            .price-value {
                font-size: 30px;
            }
        }
    </style>  
</head>  
<body>  
    <?php include 'navbar.php'; ?>  

    <!-- Hero Section -->
    <section class="villa-hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h2 class="hero-subtitle" data-aos="fade-up">VILLA</h2>
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">KAYU HUJUNG</h1>  
        </div>
    </section>  

    <!-- Villa Description Section -->
    <section class="villa-description">
        <div class="container">
            <div class="description-content">
                <p data-aos="fade-up">Villa Kayu Hujung merupakan villa dengan fasilitas yang lumayan lengkap, Villa ini juga memiliki 
                pemandangan indah Danau Situ Lengkong, terdapat area permainan billiard, dan gathering area 
                baik outdoor maupun indoor. Villa Kayu Hujung merupakan pilihan villa yang tepat untuk dijadikan
                destinasi staycation Anda.</p>
                
                <p data-aos="fade-up" data-aos-delay="200">Villa Kayu Hujung terletak tidak jauh dari destinasi wisata curug tujuh. Anda dapat mengunjungi 
                area tersebut tanpa harus jauh-jauh ke lokasi lain. Rencanakan staycation Anda bersama 
                Villa Situ Lengkong di Villa Kayu Hujung.</p>
            </div>
        </div>
    </section>

    <!-- Villa Gallery Section -->
    <section class="villa-gallery">
        <div class="container">
            <h2 class="section-title" data-aos="fade-down">GALLERY VILLA</h2>
            
            <div class="gallery-container">
                <div class="gallery-nav gallery-prev" id="galleryPrev">
                    <i class="fas fa-chevron-left"></i>
                </div>
                
                <div class="gallery-scroll" id="galleryScroll" data-aos="fade-up">
                    <img src="kayu_hujung/IMG_4597.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4600.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4601.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4603.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4605.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4607.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4611.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4624.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4627.jpg" alt="Villa Kayu Hujung">
                    <img src="kayu_hujung/IMG_4615.jpg" alt="Villa Kayu Hujung">
                </div>
                
                <div class="gallery-nav gallery-next" id="galleryNext">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Villa Details Section -->
    <section class="villa-details">
        <div class="container">
            <h2 class="section-title" data-aos="fade-down">DETAIL VILLA</h2>
            
            <div class="details-container">
                <div class="details-left" data-aos="fade-right">
                    <div class="price-box">
                        <p class="price-title">Harga</p>
                        <p class="price-value">Rp. 2.000.000</p>
                        <p class="price-period">per 24 jam</p>
                    </div>
                    
                    <div class="details-info">
                        <h3>Informasi Villa</h3>
                        <p>Kapasitas: Maksimal 30 Orang</p>
                        
                        <h3>Fasilitas:</h3>
                        <ul class="facilities-list">
                            <li>Kolam Renang</li>
                            <li>Area Parkir</li>
                            <li>Dapur</li>
                            <li>Ruang Meeting</li>
                            <li>Area Permainan Billiard</li>
                            <li>Smart TV</li>
                        </ul>
                    </div>
                </div>
                
                <div class="details-right" data-aos="fade-left">
                    <a href="calender.php" class="action-btn btn-primary">
                        Cek Ketersediaan
                    </a>
                    
                    <a href="booking.php" class="action-btn btn-secondary">
                        Booking Villa
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">  
        <p>&copy; 2024. All Rights Reserved Villa Panjalu</p>  
    </footer>  

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="js/navbar.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease',
            once: true
        });
        
        // Gallery scroll functionality
        document.addEventListener('DOMContentLoaded', function() {
            const galleryScroll = document.getElementById('galleryScroll');
            const galleryPrev = document.getElementById('galleryPrev');
            const galleryNext = document.getElementById('galleryNext');
            
            galleryPrev.addEventListener('click', function() {
                galleryScroll.scrollBy({ left: -320, behavior: 'smooth' });
            });
            
            galleryNext.addEventListener('click', function() {
                galleryScroll.scrollBy({ left: 320, behavior: 'smooth' });
            });
        });
    </script>
</body>
</html>