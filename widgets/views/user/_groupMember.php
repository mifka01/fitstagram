<?php

/** @var yii\web\View $this */
/** @var string|false $itemButtonLabel */
/** @var string|array<mixed> $itemButtonRoute */
/** @var string|false $updateButtonLabel */
/** @var string|array<mixed> $updateButtonRoute */
/** @var app\models\GroupMember $model */

use Yii;
use yii\helpers\Html;

?>
<div class="flex flex-col p-4 hover:bg-gray-50 transition-colors duration-150">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-3">
                <h2 class="text-lg font-medium text-gray-900 truncate">
                    <a href="<?= Yii::$app->urlManager->createUrl(['/user/profile', 'username' => $model->user->username]) ?>">
                        <?= $model->user->username ?>
                    </a>
                </h2>
            </div>

            <div class="mt-2 flex items-center space-x-2 text-sm text-gray-500">
                <span>
                    <?= Yii::t('app/group', 'Number of posts {count}', ['count' =>    $model->group->getPosts()->where(['created_by' => $model->user_id])->count()]) ?>
                    <span class="mx-1">•</span>
                    <?= Yii::t('app/group', 'Member for of this group for {time}', ['time' => Yii::$app->formatter->asRelativeTime($model->created_at)]) ?>
                </span>
            </div>

        </div>

        <div class="ml-4 flex-shrink-0 flex items-start space-x-2">

            <?php if (Yii::$app->user->can('manageGroup', ['groupId' => $model->group_id])): ?>
                <?php if ($itemButtonLabel !== false): ?>
                    <?= Html::a(
                        $itemButtonLabel,
                        $itemButtonRoute,
                        ['class' => 'inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
                    ) ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>