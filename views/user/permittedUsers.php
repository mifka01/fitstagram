<?php

/** @var yii\web\View $this */
/** @var app\models\search\PermittedUserSearch $permittedUserSearchModel */
/** @var yii\data\ActiveDataProvider $permittedUserProvider */

use app\models\User;
use app\widgets\UserWidget;
use yii\helpers\Html;

$this->title = Yii::t('app/user', 'Your Friends');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app/user', 'All your friends in one place.') ?>
        </p>
    </div>

    <?php if (!Yii::$app->user->isGuest): ?>
        <?= UserWidget::widget([
            'title' => Yii::t('app/user', 'Friends'),
            'itemButtonLabel' => Yii::t('app/user', 'Remove'),
            'itemButtonRoute' => function (User $model) {
                return ['user/revoke-permitted-user', 'id' => $model->id];
            },
            'itemView' => '_permittedUser',
            'provider' => $permittedUserProvider,
            'ajax' => true,
            'emptyMessage' => Yii::t('app/user', 'You have no friends users yet.'),
            'searchModel' => $permittedUserSearchModel
        ]) ?>
    <?php endif; ?>
</div>
