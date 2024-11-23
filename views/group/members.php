<?php

/** @var yii\web\View $this */
/** @var app\models\search\UserSearch $userSearchModel */
/** @var yii\data\ActiveDataProvider $userProvider */

use app\widgets\UserWidget;
use yii\helpers\Html;

$this->title = Yii::t('app/group', 'Members');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app/group', 'See who is part of the group.') ?>
        </p>
    </div>

    <?= UserWidget::widget([
            'title' => Yii::t('app/group', 'Group Members'),
            'itemButtonLabel' => function ($model) {
                if ($model->group->isOwner($model->user_id)) {
                    return false;
                }
                    return Yii::t('app/group', 'Remove user');
            },
            'itemButtonRoute' => function ($model) {
                if ($model->group->isOwner($model->user_id)) {
                    return false;
                }
                return ['group-membership/kick-user', 'id' => $model->group_id, 'userId' => $model->user_id];
            },
            'itemView' => '_groupMember',
            'provider' => $userProvider,
            'ajax' => true,
            'emptyMessage' => Yii::t('app/group', 'You have no members yet.'),
            'searchModel' => $userSearchModel
    ]) ?>
</div>
