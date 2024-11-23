<?php

/** @var yii\web\View $this */
/** @var string|false $itemButtonLabel */
/** @var string|array<mixed> $itemButtonRoute */
/** @var string|false $updateButtonLabel */
/** @var string|array<mixed> $updateButtonRoute */
/** @var app\models\User $model */

use Yii;
use yii\helpers\Html;

?>
<div class="flex flex-col p-4 hover:bg-gray-50 transition-colors duration-150">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-3">
                <h2 class="text-lg font-medium text-gray-900 truncate">
                    <a href="<?= Yii::$app->urlManager->createUrl(['/user/profile', 'username' => $model->username]) ?>">
                        <?= $model->username ?>
                    </a>
                </h2>
            </div>
        </div>

        <div class="ml-4 flex-shrink-0 flex items-start space-x-2">
                <?php if ($itemButtonLabel !== false): ?>
                    <?= Html::a(
                        $itemButtonLabel,
                        $itemButtonRoute,
                        ['class' => 'inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
                    ) ?>
                <?php endif; ?>
        </div>
    </div>
</div>
