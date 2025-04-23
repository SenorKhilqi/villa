document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.menu');
    
    if (menuToggle && menu) {
        menuToggle.addEventListener('click', function() {
            menu.classList.toggle('show');
            
            // Accessibility
            const expanded = menu.classList.contains('show');
            menuToggle.setAttribute('aria-expanded', expanded);
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!menuToggle.contains(event.target) && !menu.contains(event.target) && menu.classList.contains('show')) {
                menu.classList.remove('show');
                menuToggle.setAttribute('aria-expanded', false);
            }
        });
    }
});
