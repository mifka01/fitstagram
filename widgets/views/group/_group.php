<?php
/** @var yii\web\View $this */
/** @var string $title */
/** @var string|false $itemButtonLabel */
/** @var string|array<mixed> $itemButtonRoute */
/** @var string|false $actionButtonLabel */
/** @var string|array<mixed> $actionButtonRoute */
/** @var bool $ajax */
/** @var string $emptyMessage */
/** @var yii\data\ActiveDataProvider $provider */

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
?>

<div class="sm:mx-auto sm:w-full sm:max-w-3xl">
    <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
        <?= Html::encode($title) ?>
    </h1>

    <div class="bg-white shadow sm:rounded-lg flex flex-col">
        <?php if ($provider->getCount() !== 0 && $actionButtonLabel !== false): ?>
            <div class="flex flex-grow">
                    <?= Html::a(
                        $actionButtonLabel,
                        $actionButtonRoute,
                        ['class' => '
                            w-full
                            sm:w-auto
                            text-center
                            px-4 py-2 border border-transparent
                            text-sm font-medium rounded-md text-white bg-orange-600
                            hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                            transition duration-150 ease-in-out m-4'
                        ]
                    ) ?>
            </div>
        <?php endif; ?>

        <?php if ($ajax): ?>
            <?php Pjax::begin(); ?>
        <?php endif; ?>
            <?= ListView::widget([
            'dataProvider' => $provider,
            'itemView' => function ($model, $key, $index, $widget) use ($itemButtonLabel, $itemButtonRoute) {
                $route = is_callable($itemButtonRoute)
                    ? call_user_func($itemButtonRoute, $model)
                    : $itemButtonRoute;

                return $this->render('_item', [
                    'model' => $model,
                    'itemButtonLabel' => $itemButtonLabel,
                    'itemButtonRoute' => $route,
                ]);
            },
                'itemOptions' => ['class' => 'border-b border-gray-200 last:border-b-0'],
                'options' => ['class' => 'mt-2'],
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
