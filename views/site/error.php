<?php
/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;

preg_match('/^(.+?) \(#(\d+)\)$/', $name, $matches);
$errorMessage = $matches[1] ?? $name;
$errorCode = $matches[2] ?? '';

?>
<div class="bg-gray-50 flex items-center justify-center px-4 py-16 sm:px-6 lg:px-8">
    <div class="max-w-2xl text-center">
        <div class="space-y-8">
            <div class="text-orange-600 text-8xl font-bold">
                #<?= Html::encode($errorCode) ?>
            </div>

            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                <?= Html::encode(Yii::t('app/error', $errorMessage)) ?>
            </h1>

            <div class="text-lg text-gray-600">
                <?= nl2br(Html::encode(Yii::t('app/error', $message))) ?>
            </div>

            <div class="space-y-4">
                <p class="text-base text-gray-600">
                    <?= Yii::t('app/error', 'The above error occurred while the Web server was processing your request.') ?>
                </p>
                <p class="text-base text-gray-600">
                    <?= Yii::t('app/error', 'Please contact us if you think this is a server error. Thank you.') ?>
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                <?= Html::a(
                    Yii::t('app/error', 'Return to Home'),
                    ['site/index'],
                    ['class' => 'px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out']
                ) ?>
                <?= Html::a(
                    Yii::t('app/error', 'Contact Support'),
                    ['site/contact'],
                    ['class' => 'px-5 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out']
                ) ?>
            </div>
        </div>
    </div>
</div>
