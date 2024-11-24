<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'About Us';
?>
<div class="bg-gray-50 flex items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
    <div class="max-w-2xl text-center">
        <div class="space-y-8">
            <h1 class="text-4xl font-bold tracking-tight text-orange-600 sm:text-5xl">
                <?= Html::encode(Yii::t('app', 'About Us')) ?>
            </h1>

            <div class="text-lg text-gray-600">
                <p>
                    <?= Yii::t(
                        'app',
                        'Fitstagram is a college project created as a platform for sharing photos. It’s a space where users can showcase moments, creativity, and experiences while building a engaging community.'
                    ) ?>
                </p>
            </div>

            <div class="space-y-4">
                <p class="text-base text-gray-600">
                    <?= Yii::t(
                        'app',
                        'Share your photos with others or reach out to us for support.'
                    ) ?>
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                <?= Html::a(
                    Yii::t('app', 'Add Post'),
                    ['post/create'],
                    ['class' => 'px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out']
                ) ?>
                <?= Html::a(
                    Yii::t('app', 'Contact Us'),
                    ['site/contact'],
                    ['class' => 'px-5 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out']
                ) ?>
            </div>
        </div>
    </div>
</div>