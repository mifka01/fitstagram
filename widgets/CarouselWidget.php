<?php

namespace app\widgets;

use yii\base\Widget;

class CarouselWidget extends Widget
{
    /**
     * @var array<int, string> $mediaFiles
     */
    public array $mediaFiles = [];

    public function run()
    {
        $this->registerClientScript();
        return $this->renderCarousel();
    }

    protected function renderCarousel(): string
    {
        return $this->render('carousel/_carousel', [
            'mediaFiles' => $this->mediaFiles,
        ]);
    }

    protected function registerClientScript(): void
    {
        $js = <<<JS
        jQuery(function ($) {
            $(document).ready(function() {
                // Initial setup for toggle buttons
                initializeCarousel();

                const observer = new MutationObserver(() => {
                    initializeCarousel();
                });

                observer.observe(document.querySelector("#w0"), {
                    childList: true,
                });
            });

            // Function to initialize the carousel
            function initializeCarousel() {
                $('[data-carousel="static"]').each(function() {
                    const carousel = this;
                    const carouselItems = carousel.querySelectorAll('[data-carousel-item]');
                    const carouselPrev = carousel.querySelector('[data-carousel-prev]');
                    const carouselNext = carousel.querySelector('[data-carousel-next]');
                    let currentSlide = 0;

                    // Initialize carousel items
                    carouselItems.forEach((item, index) => {
                        item.classList.toggle('hidden', index !== currentSlide);
                    });

                    const updateControls = () => {
                        carouselPrev.classList.toggle('hidden', currentSlide === 0);
                        carouselNext.classList.toggle('hidden', currentSlide === carouselItems.length - 1);
                    };

                    updateControls();

                    // Use jQuery to add event listeners for previous/next buttons
                    $(carouselPrev).off('click').on('click', function() {
                        carouselItems[currentSlide].classList.add('hidden');
                        currentSlide = (currentSlide - 1 + carouselItems.length) % carouselItems.length;
                        carouselItems[currentSlide].classList.remove('hidden');
                        updateControls();
                    });

                    $(carouselNext).off('click').on('click', function() {
                        carouselItems[currentSlide].classList.add('hidden');
                        currentSlide = (currentSlide + 1) % carouselItems.length;
                        carouselItems[currentSlide].classList.remove('hidden');
                        updateControls();
                    });
                });
            }
        });
JS;

        $view = $this->getView();
        $view->registerJs($js);
    }
}
