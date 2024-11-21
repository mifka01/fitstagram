<?php
/** @var yii\web\View $this */
/** @var string $title */
/** @var yii\data\ActiveDataProvider $provider */
/** @var string|false $itemButtonLabel */
/** @var string|array<mixed> $itemButtonRoute */
/** @var string|false $updateButtonLabel */
/** @var string|array<mixed> $updateButtonRoute */
/** @var string|false $actionButtonLabel */
/** @var string|array<mixed> $actionButtonRoute */
/** @var bool $ajax */
/** @var string $emptyMessage */
/** @var yii\base\Model|false $searchModel */
/** @var string $searchParam */


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;
?>

<div class="sm:mx-auto sm:w-full sm:max-w-3xl">
    <h1 class="px-4 text-2xl font-semibold tracking-tight text-gray-900">
        <?= Html::encode($title) ?>
    </h1>
    <?php if ($ajax): ?>
        <?php Pjax::begin(); ?>
    <?php endif; ?>

    <div class="bg-white shadow sm:rounded-lg flex flex-col mt-2">
        <?php if ($actionButtonLabel !== false || $searchModel !== false): ?>
            <div class="sm:flex-row flex flex-col justify-between border-b border-gray-200">
        <?php endif; ?>
            <?php if ($provider->getCount() !== 0 && $actionButtonLabel !== false): ?>
                <?= Html::a(
                    $actionButtonLabel,
                    $actionButtonRoute,
                    ['class' => '
                        m-4
                        text-center
                        px-4 py-2 border border-transparent
                        text-sm font-medium rounded-md text-white bg-orange-600
                        hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                        transition duration-150 ease-in-out'
                    ]
                ) ?>
            <?php endif; ?>
        <?php if ($searchModel !== false): ?>
            <?php $form = ActiveForm::begin([
                    'id' => 'group-search-form-' . $searchModel->formName(),
                    'method' => 'get',
                    'options' => [
                        'class' => 'm-4',
                        'data-pjax' => $ajax,
                    ],
            ]); ?>
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
                <?= $form->field($searchModel, $searchParam, [
                        'options' => ['class' => 'flex-grow'],
                        'template' => "{input}\n{error}",
                ])->textInput([
                    'allowClear' => true,
                    'class' => '
                        w-full
                        sm:w-auto
                        px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500',
                        'placeholder' => Yii::t('app/group', 'Search groups...'),
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        <?php endif; ?>
        <?php if ($actionButtonLabel !== false || $searchModel !== false): ?>
            </div>
        <?php endif; ?>

        <?= ListView::widget([
        'dataProvider' => $provider,
        'itemView' => function ($model, $key, $index, $widget) use ($itemButtonLabel, $itemButtonRoute, $updateButtonLabel, $updateButtonRoute) {

            $label = is_callable($itemButtonLabel)
            ? call_user_func($itemButtonLabel, $model)
            : $itemButtonLabel;

            $route = is_callable($itemButtonRoute)
            ? call_user_func($itemButtonRoute, $model)
            : $itemButtonRoute;

            $updateRoute = is_callable($updateButtonRoute)
            ? call_user_func($updateButtonRoute, $model)
            : $updateButtonRoute;

            return $this->render('_item', [
            'model' => $model,
            'itemButtonLabel' => $label,
            'itemButtonRoute' => $route,
            'updateButtonLabel' => $updateButtonLabel,
            'updateButtonRoute' => $updateRoute,
            ]);
        },
            'itemOptions' => ['class' => 'border-b border-gray-200 last:border-b-0'],
            'layout' => "{items}\n{pager}",
            'pager' => [
                'options' => ['class' => 'flex justify-center gap-2 my-4'],
                'linkOptions' => ['class' => 'px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50'],
                'activePageCssClass' => 'border-orange-500 bg-orange-50 text-orange-600',
                'disabledPageCssClass' => 'border-gray-200 text-gray-400 cursor-not-allowed',
        ],
        'emptyText' => $this->render('_empty', [
                'title' => $title,
                'emptyMessage' => $emptyMessage,
                'actionButtonLabel' => $actionButtonLabel,
                'actionButtonRoute' => $actionButtonRoute,
            ]),
        ]); ?>
        <?php if ($ajax): ?>
            <?php Pjax::end(); ?>
        <?php endif; ?>
    </div>
</div>
