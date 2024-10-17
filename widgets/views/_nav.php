<?php

/**
 * @var yii\web\View $this
 * @var array<int, array{label: string, route: string|array<string|array<string, string>>, visible?: bool, icon?: string}> $items
 * @var array<int, array{label: string, route: string|array<string|array<string, string>>, visible?: bool, icon?: string}>|false $actionButton
 * @var string|false $brandLabel
 * @var string|false $brandImage
 * @var string|false $brandUrl
 */

use yii\helpers\Url;

$currentActionButton = false;
if ($actionButton) {
    foreach ($actionButton as $button) {
        if (!isset($button['visible']) || $button['visible']) {
            $currentActionButton = $button;
            break;
        }
    }
}

?>

<nav class="bg-gray-50 py-2 
            fixed bottom-0 left-0 right-0
            md:static md:top-0 md:bottom-auto 
    ">

    <!-- Mobile menu -->
    <div class="md:hidden">
        <div class="
            flex
            justify-between items-center
            text-lg text-gray-700
            rounded-lg 
            py-2
            divide-x-2 divide-gray-300">
            <?php foreach ($items as $item): ?>
              <a href="<?= Url::to($item['route']) ?>" class="
                w-full text-center
                <?php if (isset($item['icon'])): ?>
                    <?= $item['icon'] ?>
                <?php else: ?>
                    <?= $item['label']?>
                <?php endif; ?>
                    ">
            </a>
            <?php endforeach; ?>
            <?php if ($currentActionButton): ?>
                <a href="<?= Url::to($currentActionButton['route']) ?>" class="
                w-full text-center
                <?= $currentActionButton['icon'] ?? '' ?>
                ">
                    <?php if (!isset($currentActionButton['icon'])): ?>
                        <?=Yii::t('app/user', $currentActionButton['label'])?>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <!-- Mobile menu -->


    <!-- Desktop menu -->
    <div class="
        hidden md:flex
        container relative
        justify-between items-center
        mx-auto px-4">
        <div class="flex-initial">
                <?php if ($brandUrl): ?>
            <a href="<?= Url::to($brandUrl) ?>" class="
                flex flex-shrink-0 items-center space-x-2
                font-semibold text-lg text-gray-900
                ">
                    <?php if ($brandImage): ?>
                <img class="h-8 w-auto" src="<?= $brandImage ?>" alt="<?= $brandLabel?>">
                    <?php endif; ?>
                <span><?= $brandLabel ?></span>
            </a>
                <?php else: ?>
            <span class="
                flex flex-shrink-0 items-center space-x-2
                font-semibold text-lg text-gray-900">
                    <?php if ($brandImage): ?>
                <img class="h-8 w-auto" src="<?= $brandImage ?>" alt="<?= $brandLabel?>">
                    <?php endif; ?>
                    <?= $brandLabel ?>
            </span>
                <?php endif; ?>
        </div>
        <div class="
            flex
            text-lg text-gray-700
            rounded-lg 
            py-2
            divide-x-2 divide-gray-300">
                <?php foreach ($items as $item): ?>
              <a href="<?= Url::to($item['route']) ?>" class="
                    px-8
                    relative z-0

                    after:absolute
                    after:inset-0
                    after:px-8
                
                    after:bg-gray-200/60
                    after:rounded-full
                    after:hover:scale-[0.9]
                    after:scale-0
                    after:transition-transform
                    after:duration-300 
                    after:-z-10
                    after:shadow-md

                    <?php if (Yii::$app->requestedRoute === $item['route'][0]): ?>
                        font-semibold
                    <?php endif; ?>
                    ">
                    <?= $item['label'] ?>
            </a>
                <?php endforeach; ?>
        </div>
        <?php if ($currentActionButton): ?>
        <a href="<?= Url::to($currentActionButton['route'])?>" class="
                    rounded-full
                    border border-black
                    bg-black text-white
                    px-12 py-4
                    relative z-0
                    overflow-hidden

                    transition-color duration-300
                    hover:text-black

                    after:-z-10
                    after:rounded-full
                    after:text-center
                    after:absolute
                    after:top-full
                    after:left-0
                    after:w-full
                    after:h-full
                    after:bg-orange-400
                    after:transition-all
                    after:duration-300

                    after:border
                    after:border-black

                    hover:after:top-0
            ">
                <?=Yii::t('app/user', $currentActionButton['label'])?>
            </a>
        <?php endif; ?>
    </div>
    <!-- Desktop menu -->
</nav>

