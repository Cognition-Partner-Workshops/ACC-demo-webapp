/**
 * Mobile navigation toggle
 */
(function() {
    'use strict';

    var hamburger = document.querySelector('.main-header__hamburger');
    var nav = document.querySelector('.main-nav');

    if (hamburger && nav) {
        hamburger.addEventListener('click', function() {
            nav.classList.toggle('main-nav--open');
        });
    }
})();
