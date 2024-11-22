<?php

/** @var yii\web\View $this */
/** @var app\models\search\PermittedUserSearch $permittedUserSearchModel */
/** @var yii\data\ActiveDataProvider $permittedUserProvider */

use app\models\PermittedUser;
use app\widgets\UserWidget;
use yii\helpers\Html;

$this->title = Yii::t('app/group', 'Permitted Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app/group', 'See who is permitted user.') ?>
        </p>
    </div>

    <?php if (!Yii::$app->user->isGuest): ?>
        <?= UserWidget::widget([
            'title' => Yii::t('app/group', 'Permitted Users'),
            'itemButtonLabel' => Yii::t('app/group', 'Revoke'),
            'itemButtonRoute' => function (PermittedUser $model) {
                return ['user/remove-permitted-user', 'id' => $model->user_id, 'permittedUserId' => $model->permitted_user_id];
            },
            'itemView' => '_permittedUser',
            'provider' => $permittedUserProvider,
            'ajax' => true,
            'emptyMessage' => Yii::t('app/group', 'You have no permited users yet.'),
            'searchModel' => $permittedUserSearchModel
        ]) ?>
    <?php endif; ?>
</div>
