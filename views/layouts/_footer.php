<?php
/**
 * @var yii\web\View $this
 */

use app\widgets\LanguageSelector;
use yii\helpers\Html;

?>
<footer class="hidden md:block mt-auto bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                &copy; <?= date('Y') ?> <?= Yii::$app->name ?>
            </div>

            <nav class="flex space-x-6">
                <?= Html::a(
                    Yii::t('app', 'About'),
                    ['site/about'],
                    ['class' => 'text-sm text-gray-500 hover:text-orange-500 transition']
                ) ?>
                <?= Html::a(
                    Yii::t('app', 'Contact'),
                    ['site/contact'],
                    ['class' => 'text-sm text-gray-500 hover:text-orange-500 transition']
                ) ?>
                <?= Html::a(
                    Yii::t('app', 'Privacy'),
                    ['site/privacy'],
                    ['class' => 'text-sm text-gray-500 hover:text-orange-500 transition']
                ) ?>
            </nav>
            <?= LanguageSelector::widget() ?>
        </div>
    </div>
</footer>
