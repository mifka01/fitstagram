<?php

use app\models\User;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\search\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900"><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => LinkPager::class,
            'options' => ['class' => 'flex justify-center gap-2 my-4'],
            'linkOptions' => ['class' => 'page-link px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50'],
            'activePageCssClass' => 'border-orange-500 bg-orange-50 text-orange-600',
            'disabledPageCssClass' => 'border-gray-200 text-gray-400 cursor-not-allowed',
        ],
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'min-width:100px'],
                'filterInputOptions' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/admin', 'Enter ID'),
                ],
            ],
            [
                'attribute' => 'username',
                'headerOptions' => ['style' => 'min-width:200px'],
                'filterInputOptions' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/admin', 'Enter Username'),
                ],
            ],
            [
                'attribute' => 'email',
                'format' => 'email',
                'headerOptions' => ['style' => 'min-width:250px'],
                'filterInputOptions' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/admin', 'Enter Email'),
                ],
            ],
            [
            'label' => Yii::t('app/admin', 'Role'),
            'value' => function (User $model) {
                $auth = Yii::$app->authManager;
                if ($auth && ($roles = $auth->getRolesByUser($model->id))) {
                    $role = reset($roles);
                    return $role->name;
                }
                return null;
            },
            'filter' => [
                'user' => Yii::t('app/admin', 'User'),
                'moderator' => Yii::t('app/admin', 'Moderator'),
                'admin' => Yii::t('app/admin', 'Admin'),
                ],
            ],
            [
                'attribute' => 'active',
                'class' => 'kartik\grid\BooleanColumn',
                'filterWidgetOptions' => ['hideSearch' => true],
                'filterInputOptions' => [
                    'placeholder' => Yii::t('app/admin', 'Select...'),
                ],
                'useSelect2Filter' => true,
                'value' => function (User $model) {
                    return $model->active;
                },
                'trueLabel' => Yii::t('app/admin', 'Yes'),
                'falseLabel' => Yii::t('app/admin', 'No'),
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            [
                'attribute' => 'deleted',
                'label' => Yii::t('app/admin', 'Deleted'),
                'class' => 'kartik\grid\BooleanColumn',
                'filterWidgetOptions' => ['hideSearch' => true],
                'filterInputOptions' => [
                    'placeholder' => Yii::t('app/admin', 'Select...'),
                ],
                'useSelect2Filter' => true,
                'value' => function (User $model) {
                    return $model->deleted;
                },
                'trueLabel' => Yii::t('app/admin', 'Yes'),
                'falseLabel' => Yii::t('app/admin', 'No'),
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            [
            'attribute' => 'banned',
            'label' => Yii::t('app/admin', 'Banned'),
                'class' => 'kartik\grid\BooleanColumn',
                'filterWidgetOptions' => ['hideSearch' => true],
                'filterInputOptions' => [
                    'placeholder' => Yii::t('app/admin', 'Select...'),
                ],
                'useSelect2Filter' => true,
                'value' => function (User $model) {
                    return $model->banned;
                },
                'trueLabel' => Yii::t('app/admin', 'Yes'),
                'falseLabel' => Yii::t('app/admin', 'No'),
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
