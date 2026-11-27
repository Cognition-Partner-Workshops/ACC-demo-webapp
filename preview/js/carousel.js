/**
 * Hero Carousel — mirrors hero-carousel.js behavior
 * Auto-advance every 5s, dot navigation, prev/next arrows, wrap-around
 */
(function() {
    'use strict';

    var container = document.querySelector('[data-role="hero-carousel"]');
    if (!container) return;

    var slides = container.querySelectorAll('.hero-banner__slide');
    var dots = container.querySelectorAll('.hero-banner__dot');
    var prevBtn = container.querySelector('.hero-banner__arrow--prev');
    var nextBtn = container.querySelector('.hero-banner__arrow--next');
    var currentIndex = 0;
    var intervalId = null;
    var delay = 5000;

    function showSlide(index) {
        slides.forEach(function(s) { s.classList.remove('hero-banner__slide--active'); });
        dots.forEach(function(d) { d.classList.remove('active'); });
        slides[index].classList.add('hero-banner__slide--active');
        dots[index].classList.add('active');
        currentIndex = index;
    }

    function nextSlide() {
        showSlide((currentIndex + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((currentIndex - 1 + slides.length) % slides.length);
    }

    function startAutoAdvance() {
        intervalId = setInterval(nextSlide, delay);
    }

    function stopAutoAdvance() {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
        }
    }

    dots.forEach(function(dot) {
        dot.addEventListener('click', function() {
            var slideIndex = parseInt(this.getAttribute('data-slide'), 10);
            stopAutoAdvance();
            showSlide(slideIndex);
            startAutoAdvance();
        });
    });

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            stopAutoAdvance();
            prevSlide();
            startAutoAdvance();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            stopAutoAdvance();
            nextSlide();
            startAutoAdvance();
        });
    }

    if (slides.length > 1) {
        startAutoAdvance();
    }
})();
