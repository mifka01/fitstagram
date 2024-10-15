<?php

/** @var yii\web\View $this */
/** @var array<string, string|array<string>> $items */
/** @var string|bool $brandLabel */
/** @var string|bool $brandImage */
/** @var string|bool $brandUrl */

use yii\helpers\Url;
?>

<nav class="bg-gray-50 py-2">
    <div class="relative flex justify-between items-center mx-auto px-4 container">
        <div class="flex-initial">
            <?php if ($brandImage): ?>
                <img class="h-8 w-auto" src="<?= $brandImage ?>" alt="<?= $brandLabel?>">
            <?php elseif ($brandUrl): ?>
                <a href="<?= $brandUrl ?>" class="font-semibold text-lg text-gray-900"><?= $brandLabel ?></a>
            <?php else: ?>
                <span class="font-semibold text-lg text-gray-900"><?= $brandLabel ?></span>
            <?php endif; ?>
        </div>
        <div class="
            flex
            text-lg text-gray-700
            rounded-lg 
            py-2
            divide-x-2 divide-gray-300">
            <?php foreach ($items as $label => $route): ?>
              <a href="<?= Url::to($route) ?>" class="
                    px-8
                    relative z-0

                    after:absolute
                    after:inset-0
                    after:px-8
                
                    after:bg-gray-200/60
                    after:rounded-full
                    after:scale-0
                    after:hover:scale-[0.9]
                    after:transition-transform
                    after:duration-300 
                    after:-z-10
                    after:shadow-md

                    <?php if (Yii::$app->requestedRoute === $route[0]): ?>
                        font-semibold
                    <?php endif; ?>
                    ">
                    <?= $label ?>
            </a>
            <?php endforeach; ?>
        </div>
        <button class="
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
            <?=Yii::t('app/user', 'Profile')?>
        </button>
    </div>
</nav>

