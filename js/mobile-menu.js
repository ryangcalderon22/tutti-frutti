(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.getElementById('mobileMenuToggle');
        const navMenu = document.getElementById('site-navigation');

        if (!hamburger || !navMenu) return;

        hamburger.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMenu();
        });

        function toggleMenu() {
            const menu = document.querySelector('#site-navigation');
            const isActive = menu.classList.contains('active');
            
            if (isActive) {
                menu.classList.remove('active');
                hamburger.classList.remove('active');
            } else {
                menu.classList.add('active');
                hamburger.classList.add('active');
            }
        }

        // Close menu on link click
        const menuLinks = navMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });

        // Close menu on outside click
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
    });

})();
