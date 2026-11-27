/**
 * Copyright © Example Co. All rights reserved.
 */

define(['jquery'], function ($) {
    'use strict';

    return function (config, element) {
        var $container = $(element),
            $slides = $container.find('.hero-banner__slide'),
            $dots = $container.find('.hero-banner__dot'),
            currentIndex = 0,
            intervalId = null,
            delay = (config && config.delay) || 5000;

        /**
         * Show the slide at the given index.
         *
         * @param {Number} index
         */
        function showSlide(index) {
            $slides.removeClass('hero-banner__slide--active');
            $dots.removeClass('active');
            $slides.eq(index).addClass('hero-banner__slide--active');
            $dots.eq(index).addClass('active');
            currentIndex = index;
        }

        /**
         * Advance to the next slide, wrapping around.
         */
        function nextSlide() {
            showSlide((currentIndex + 1) % $slides.length);
        }

        /**
         * Start the auto-advance timer.
         */
        function startAutoAdvance() {
            intervalId = setInterval(nextSlide, delay);
        }

        /**
         * Stop the auto-advance timer.
         */
        function stopAutoAdvance() {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }

        $dots.on('click', function () {
            var slideIndex = parseInt($(this).data('slide'), 10);

            stopAutoAdvance();
            showSlide(slideIndex);
            startAutoAdvance();
        });

        if ($slides.length > 1) {
            startAutoAdvance();
        }
    };
});
