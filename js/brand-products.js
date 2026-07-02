(function () {
    'use strict';

    document.querySelectorAll('.brand-products').forEach(function (wrap) {
        var toggle = wrap.querySelector('.brand-products__toggle');
        if (!toggle) {
            return;
        }

        var expandLabel = toggle.textContent;
        toggle.setAttribute('data-label-expand', expandLabel);
        toggle.setAttribute('data-label-collapse', 'Show Less');

        toggle.addEventListener('click', function () {
            var expanded = wrap.classList.toggle('is-expanded');
            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
            toggle.textContent = expanded
                ? toggle.getAttribute('data-label-collapse')
                : toggle.getAttribute('data-label-expand');
        });
    });
})();
