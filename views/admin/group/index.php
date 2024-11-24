<?php

use app\models\Group;
use kartik\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\search\GroupSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app/admin', 'Groups');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-index">
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
                'attribute' => 'name',
                'headerOptions' => ['style' => 'min-width:200px'],
                'filterInputOptions' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/admin', 'Enter Name'),
                ],
            ],
            [
                'attribute' => 'description',
                'format' => 'ntext',
                'headerOptions' => ['style' => 'min-width:300px'],
                'filterInputOptions' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/admin', 'Enter Description'),
                ],
            ],
            [
                'attribute' => 'owner_id',
                'value' => function ($model) {
                    return $model->owner->username;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filterWidgetOptions' => [
                    'options' => ['prompt' => ''],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'ajax' => [
                            'url' => Url::to(['user-list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(model) { return model.text; }'),
                        'templateSelection' => new JsExpression('function (model) { return model.text; }'),
                    ],
                ],
                'headerOptions' => ['style' => 'min-width:200px'],
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
                'value' => function (Group $model) {
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
                'value' => function (Group $model) {
                    return $model->banned;
                },
                'trueLabel' => Yii::t('app/admin', 'Yes'),
                'falseLabel' => Yii::t('app/admin', 'No'),
                'headerOptions' => ['style' => 'min-width:150px'],
            ],
            [
                'class' => ActionColumn::class,
                'template' => '{view} {delete}',
                'urlCreator' => function ($action, Group $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
