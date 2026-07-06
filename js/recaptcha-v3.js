(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof tfRecaptcha === 'undefined' || !tfRecaptcha.siteKey) {
            return;
        }

        var forms = document.querySelectorAll('form[data-recaptcha="1"]');

        forms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                var field = form.querySelector('input[name="g-recaptcha-response"]');

                if (!field || typeof grecaptcha === 'undefined') {
                    // No field to fill or reCAPTCHA failed to load — let the
                    // form submit normally and fail gracefully server-side.
                    return;
                }

                if (form.dataset.recaptchaDone === '1') {
                    // Already got a token and this is the JS-triggered
                    // re-submit; let it go through normally.
                    return;
                }

                e.preventDefault();

                var submitted = false;
                var doSubmit = function () {
                    if (submitted) {
                        return;
                    }
                    submitted = true;
                    form.dataset.recaptchaDone = '1';

                    // form.submit() does NOT include the clicked submit
                    // button's name/value pair (per the HTML spec), but the
                    // PHP handler relies on that field being present to know
                    // the form was submitted. Use requestSubmit() when
                    // available so it behaves like a real button click; fall
                    // back to manually mirroring the button's name/value.
                    var submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');

                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit(submitBtn || undefined);
                        return;
                    }

                    if (submitBtn && submitBtn.name) {
                        var hidden = form.querySelector('input[type="hidden"][name="' + submitBtn.name + '"]');
                        if (!hidden) {
                            hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = submitBtn.name;
                            form.appendChild(hidden);
                        }
                        hidden.value = submitBtn.value || '1';
                    }

                    form.submit();
                };

                // Safety net: if reCAPTCHA never calls back (blocked domain,
                // network issue, ad blocker, etc.) don't leave the visitor
                // stuck — submit anyway after a short wait and let the
                // server-side check handle it.
                var fallbackTimer = setTimeout(doSubmit, 4000);

                try {
                    grecaptcha.ready(function () {
                        grecaptcha
                            .execute(tfRecaptcha.siteKey, { action: tfRecaptcha.action || 'submit' })
                            .then(function (token) {
                                clearTimeout(fallbackTimer);
                                field.value = token;
                                doSubmit();
                            })
                            .catch(function () {
                                clearTimeout(fallbackTimer);
                                doSubmit();
                            });
                    });
                } catch (err) {
                    clearTimeout(fallbackTimer);
                    doSubmit();
                }
            });
        });
    });
})();
