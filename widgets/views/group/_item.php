<?php
/** @var yii\web\View $this */
/** @var string|false $itemButtonLabel */
/** @var string|array<mixed> $itemButtonRoute */
/** @var string|false $updateButtonLabel */
/** @var string|array<mixed> $updateButtonRoute */
/** @var app\models\Group $model */
/** @var bool $linkOnTitle */

use Yii;
use yii\helpers\Html;

?>
<div class="flex flex-col p-4 hover:bg-gray-50 transition-colors duration-150">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-3">
                <h2 class="text-lg font-medium text-gray-900 truncate">
                    <?= $linkOnTitle ? Html::a(Html::encode($model->name), $itemButtonRoute) : Html::encode($model->name) ?>
                </h2>
                <span class="inline-flex justify-center items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 text-nowrap">
                    <?= Yii::$app->formatter->asMemberCount($model->getMembers()->count()) ?>
                </span>
            </div>

            <?php if (!empty($model->description)): ?>
                <div class="description-container">
                    <p class="mt-1 text-sm text-gray-600 line-clamp-2 description">
                        <?= Html::encode($model->description) ?>
                    </p>
                    <button class="hidden expand-button mt-1 text-sm text-orange-600 hover:text-orange-700 focus:outline-none focus:underline">
                        Show more
                    </button>
                </div>
            <?php endif; ?>

            <div class="mt-2 flex items-center space-x-2 text-sm text-gray-500">
                <span><?= Html::encode($model->owner->username) ?></span>
                <span>•</span>
                <span><?= Yii::$app->formatter->asRelativeTime($model->updated_at) ?></span>
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

            <?php if (Yii::$app->user->id === $model->owner_id): ?>
                <?php if ($updateButtonLabel !== false): ?>
                    <?= Html::a(
                        $updateButtonLabel,
                        $updateButtonRoute,
                        ['class' => 'inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
                    ) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
