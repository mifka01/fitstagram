<?php

/** @var yii\web\View $this */
/** @var app\models\search\UserGroupSearch $publicSearchModel */
/** @var app\models\search\UserGroupSearch $ownedSearchModel */
/** @var app\models\search\UserGroupSearch $joinedSearchModel */
/** @var yii\data\ActiveDataProvider $publicGroupsProvider */
/** @var yii\data\ActiveDataProvider $ownedGroupsProvider */
/** @var yii\data\ActiveDataProvider $joinedGroupsProvider */

use app\models\GroupJoinRequest;
use app\models\GroupMember;
use app\widgets\GroupWidget;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Groups');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app/group', 'Groups are a wat to connect with other people who share your interests.') ?>
        </p>
    </div>

    <?php if (!Yii::$app->user->isGuest): ?>
        <?= GroupWidget::widget([
            'title' => Yii::t('app/group', 'Created Groups'),
            'itemButtonLabel' => Yii::t('app/group', 'View'),
            'itemButtonRoute' => function ($model) {
                return ['group/view', 'id' => $model->id];
            },
            'actionButtonLabel' => Yii::t('app/group', 'Create New Group'),
            'actionButtonRoute' => ['group/create'],
            'provider' => $ownedGroupsProvider,
            'ajax' => true,
            'emptyMessage' => Yii::t('app/group', 'You have not created any groups yet.'),
            'searchModel' => $ownedSearchModel
        ]) ?>

        <?= GroupWidget::widget([
            'title' => Yii::t('app/group', 'Joined Groups'),
            'itemButtonLabel' => Yii::t('app/group', 'View'),
            'itemButtonRoute' => function ($model) {
                return ['group/view', 'id' => $model->id];
            },
            'provider' => $joinedGroupsProvider,
            'ajax' => true,
            'emptyMessage' => Yii::t('app/group', 'You have not joined any groups yet.'),
            'searchModel' => $joinedSearchModel
        ]) ?>
    <?php endif; ?>

    <?= GroupWidget::widget([
        'title' => Yii::t('app/group', 'Public Groups'),
        'itemButtonLabel' => function ($model) {
            if (GroupJoinRequest::find()->pending()->forGroup($model->id)->byCurrentUser()->exists()) {
                return Yii::t('app/group', 'Request Pending');
            } elseif (GroupMember::find()->byGroup($model->id)->byCurrentUser()->exists()) {
                return Yii::t('app/group', 'View');
            } else {
                return Yii::t('app/group', 'Join');
            }
        },
        'itemButtonRoute' => function ($model) {
            if (Yii::$app->user->isGuest) {
                return ['auth/login'];
            } elseif (GroupJoinRequest::find()->pending()->forGroup($model->id)->byCurrentUser()->exists()) {
                return ['group-membership/request-cancel', 'id' => $model->id];
            } elseif (GroupMember::find()->byGroup($model->id)->byCurrentUser()->exists()) {
                return ['group/view', 'id' => $model->id];
            } else {
                return ['group-membership/request-join', 'id' => $model->id];
            }
        },
        'provider' => $publicGroupsProvider,
        'ajax' => true,
        'emptyMessage' => Yii::t('app/group', 'There are no public groups yet.'),
        'searchModel' => $publicSearchModel
    ]) ?>

</div>