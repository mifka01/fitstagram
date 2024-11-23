<?php

use yii\helpers\Html;

/** @var \app\models\MediaFile[] $mediaFiles */

?>

<div id="animation-carousel" class="relative w-full" data-carousel="static">
    <!-- Carousel wrapper -->
    <div class="relative  overflow-hidden rounded-lg">
        <!-- Items -->
        <?php foreach ($mediaFiles as $index => $mediaFile): ?>
        <div class="<?= $index === 0 ? 'relative w-full flex items-center justify-center' : 'hidden relative w-full h-full flex items-center justify-center' ?>" data-carousel-item>
            <img src="/<?= Html::encode($mediaFile->path ?? '') ?>"
                class="rounded-lg w-full h-full object-contain"
                alt="<?= Html::encode($mediaFile->name ?? '') ?>">
        </div>
        <?php endforeach; ?>

        <!-- Slider controls -->
        <button type="button" class="hidden absolute top-1/2 left-0 z-10 flex items-center justify-center h-full px-4 transform -translate-y-1/2" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10">
                <svg class="w-4 h-4 text-gray-700 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>

        <button type="button" class="hidden absolute top-1/2 right-0 z-10 flex items-center justify-center h-full px-4 transform -translate-y-1/2" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10">
                <svg class="w-4 h-4 text-gray-700 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>
</div>
