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
                closeSubmenus();
            } else {
                menu.classList.add('active');
                hamburger.classList.add('active');
            }
        }

        // Submenu accordion (mobile only — desktop opens on hover via CSS).
        const parents = navMenu.querySelectorAll('.menu-item-has-children');
        const isMobile = () => window.matchMedia('(max-width: 768px)').matches;

        parents.forEach(item => {
            const link = item.querySelector(':scope > a');
            if (!link) return;

            const toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'submenu-toggle';
            toggle.setAttribute('aria-expanded', 'false');
            toggle.setAttribute('aria-label', 'Toggle submenu');
            link.insertAdjacentElement('afterend', toggle);

            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const open = item.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        });

        // Close menu on link click. Parent links only toggle on mobile, so the
        // submenu stays reachable instead of navigating away immediately.
        const menuLinks = navMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const item = link.parentElement;
                if (isMobile() && item && item.classList.contains('menu-item-has-children') && link.parentElement.querySelector(':scope > .sub-menu')) {
                    e.preventDefault();
                    const open = item.classList.toggle('is-open');
                    const btn = item.querySelector(':scope > .submenu-toggle');
                    if (btn) btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                    return;
                }
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });

        // Close menu on outside click
        document.addEventListener('click', function(e) {
            if (!navMenu.contains(e.target) && !hamburger.contains(e.target)) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                closeSubmenus();
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
                closeSubmenus();
            }
        });

        function closeSubmenus() {
            navMenu.querySelectorAll('.menu-item-has-children.is-open').forEach(item => {
                item.classList.remove('is-open');
                const btn = item.querySelector(':scope > .submenu-toggle');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
        }
    });

})();
