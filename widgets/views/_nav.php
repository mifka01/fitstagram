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

<nav class="h-20 flex">
    <!-- Mobile menu -->
    <div class="fixed flex 
        h-16 left-0 right-0
        md:hidden bg-gray-50 
        justify-center items-center 
        ">
            <div class="flex flex-col text-center font-semibold text-gray-900 align-justify-center text-2xl">
                    <?php if ($brandUrl): ?>
                <a href="<?= Url::to($brandUrl) ?>">
                        <?php if ($brandImage): ?>
                            <img class="h-12 w-auto text-center mx-auto" src="<?= $brandImage ?>" alt="<?= $brandLabel?>">
                        <?php else: ?>
                            <?= $brandLabel ?>
                        <?php endif; ?>
                </a>
                    <?php else: ?>
                <span>
                        <?php if ($brandImage): ?>
                            <img class="h-8 w-auto" src="<?= $brandImage ?>" alt="<?= $brandLabel?>">
                        <?php else: ?>
                            <?= $brandLabel ?>
                        <?php endif; ?>
                </span>
                    <?php endif; ?>
            </div>
    </div>
    <div class="
            fixed
            flex
            md:hidden
            h-10
            bottom-0 left-0 right-0
            items-center
            bg-gray-50
            justify-items-center
            border-t
            text-lg text-gray-900
            ">
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
    <!-- Mobile menu -->


    <!-- Desktop menu -->
    <div class="
        h-16
        bg-gray-50
        fixed
        right-0 left-0
        hidden md:flex
        ">
        <div class='self-center flex flex-grow'>
        <div class="w-1/4 flex flex-col text-center font-semibold text-gray-900 text-2xl items-center justify-center">
                <?php if ($brandUrl): ?>
            <a href="<?= Url::to($brandUrl) ?>">
                    <?php if ($brandImage): ?>
                        <img class="h-12 w-auto" src="<?= $brandImage ?>" alt="<?= $brandLabel?>">
                    <?php else: ?>
                        <?= $brandLabel ?>
                    <?php endif; ?>
            </a>
                <?php else: ?>
            <span>
                    <?php if ($brandImage): ?>
                        <img class="h-8 w-auto" src="<?= $brandImage ?>" alt="<?= $brandLabel?>">
                    <?php else: ?>
                        <?= $brandLabel ?>
                    <?php endif; ?>
            </span>
                <?php endif; ?>
        </div>
        <div class="
            flex flex-grow
            justify-between items-center
            h-16
            text-lg text-gray-700
            rounded-lg 
            divide-x-2 divide-gray-300"
            >
                <?php foreach ($items as $item): ?>
            <a href="<?= Url::to($item['route']) ?>" class="
                    text-center
                    flex-grow
                    relative
                    transition-color duration-300

                    after:absolute
                    after:inset-0
                    after:px-8

                    hover:text-white
                    after:bg-orange-600
                    after:rounded-full
                    after:hover:scale-90
                    after:scale-0
                    after:transition-transform
                    after:duration-300 
                    after:-z-10
                    after:shadow-md

                    <?php if (Yii::$app->requestedRoute === $item['route'][0]): ?>
                    <?php endif; ?>
                    ">
                    <?= $item['label'] ?>
            </a>
                <?php endforeach; ?>
        </div>
        <?php if ($currentActionButton): ?>
        <div class='w-1/4 h-16 flex flex-col align-middle justify-center text-center'>
            <div class='flex justify-center'>
                <a href="<?= Url::to($currentActionButton['route'])?>" class="
                    py-4
                    rounded-full
                    border-rounded-full
                    border border-black
                    bg-black text-white
                    px-12
                    relative z-0
                    font-semibold
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
                    after:bg-orange-600
                    after:transition-all
                    after:duration-300

                    after:border
                    after:border-black

                    hover:after:top-0
                ">
                <?=Yii::t('app/user', $currentActionButton['label'])?>
            </a>
            <div>
        </div>
        <?php endif; ?>
    </div>
    </div>
    <!-- Desktop menu -->
</nav>

