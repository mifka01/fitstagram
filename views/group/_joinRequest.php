<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\GroupJoinRequest $model */
?>

<div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg border border-gray-200">
    <!-- User Info -->
    <div>
        <h2 class="text-sm font-medium text-gray-900">
            <a href="<?= Yii::$app->urlManager->createUrl(['/user/profile', 'username' => $model->createdBy->username]) ?>">
                <?= Html::encode($model->createdBy->username) ?>
            </a>
        </h2>
        <p class="text-xs text-gray-500">
            <?= Yii::t('app/group', 'Requested on {date}', [
                'date' => Yii::$app->formatter->asDatetime($model->created_at),
            ]) ?>
        </p>
    </div>
    <!-- Action Buttons -->
    <div class="flex space-x-2">

        <?= Html::a(
            Yii::t('app/group', 'Approve'),
            ['group-membership/approve-request', 'id' => $model->id],
            ['class' => 'm-1 w-32 py-2 self-center text-center border border-orange-600 text-sm font-medium rounded-md  bg-orange-500 text-white hover:text-orange-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
        ) ?>
        <?= Html::a(
            Yii::t('app/group', 'Decline'),
            ['group-membership/reject-request', 'id' => $model->id],
            ['class' => 'm-1 w-32 py-2 self-center text-center border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
        ) ?>
    </div>
</div>
