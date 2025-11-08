<!-- ============================================================================
     Villa Panjalu - Head Template with Tailwind CSS
     ============================================================================
     Usage: Include this file in <head> section of all pages
     Example: <?php include 'includes/head.php'; ?>
     ============================================================================ -->

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Tailwind CSS via CDN (v3.4) -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Tailwind Custom Configuration -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: '#B0A695',
                        dark: '#8A7F6C',
                        light: '#EBE3D5',
                    },
                    accent: '#776B5D',
                    villa: {
                        50: '#F8F7F5',
                        100: '#EBE3D5',
                        200: '#D4C4B0',
                        300: '#B0A695',
                        400: '#8A7F6C',
                        500: '#776B5D',
                        600: '#5C5246',
                        700: '#443C32',
                        800: '#2D2720',
                        900: '#1A1512',
                    }
                },
                fontFamily: {
                    sans: ['Poppins', 'sans-serif'],
                    display: ['Playfair Display', 'serif'],
                },
                boxShadow: {
                    'soft': '0 2px 8px rgba(0, 0, 0, 0.1)',
                    'medium': '0 4px 12px rgba(0, 0, 0, 0.15)',
                    'strong': '0 8px 24px rgba(0, 0, 0, 0.2)',
                },
            }
        }
    }
</script>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Common Legacy CSS (for gradual migration) -->
<link rel="stylesheet" href="css/common.css">

<!-- Navbar CSS (existing) -->
<link rel="stylesheet" href="css/navbar.css">

<!-- Custom Styles -->
<style>
    /* Smooth transitions for all elements */
    * {
        transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #B0A695;
        border-radius: 5px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #8A7F6C;
    }
</style>
