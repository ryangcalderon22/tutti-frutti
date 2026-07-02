(function () {
    'use strict';

    var header = document.getElementById('masthead');
    if (!header || !document.body.classList.contains('has-transparent-header')) {
        return;
    }

    var threshold = 80;
    var logoLink = header.querySelector('.custom-logo-link');
    var logoImg = header.querySelector('.custom-logo');

    function onScroll() {
        var scrolled = window.scrollY > threshold;
        header.classList.toggle('site-header--scrolled', scrolled);

        if (logoImg && logoLink) {
            var lightSrc = logoLink.getAttribute('data-logo-light');
            var darkSrc = logoLink.getAttribute('data-logo-dark');
            if (scrolled && lightSrc) {
                logoImg.setAttribute('src', lightSrc);
            } else if (!scrolled && darkSrc) {
                logoImg.setAttribute('src', lightSrc || darkSrc);
            }
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();
