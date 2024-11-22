<?php

/** @var yii\web\View $this */

use yii\data\ActiveDataProvider;

/** @var app\models\Group $model */
/** @var ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app/group', 'Group {name}', ['name' => $model->name]);
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-start">
                    <div class="flex-1">
                        <div class="flex justify-between items-center space-x-4">
                            <div>
                                <h1 class="text-xl font-bold text-gray-900 line-clamp-1">
                                    <?= Html::encode($model->name) ?>
                                </h1>
                                <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                                    <span><?= Yii::t('app/group', 'Join Requests') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-center border-t border-gray-200 pt-4">
                    <div class="text-center">
                        <span class="text-xl font-bold text-gray-900">
                            <?= Yii::$app->formatter->asInteger($dataProvider->getTotalCount()) ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/group', 'Pending Requests') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <div class="mt-4 space-y-4">

            <?= ListView::widget(
                [
                    'id' => 'list-view-posts',
                    'dataProvider' => $dataProvider,
                    'itemView' => '_joinRequest',
                    'itemOptions' => ['class' => 'item mb-2'],
                    'layout' => "{items}\n{pager}",
                    'summary' => '',
                    'emptyText' => Yii::t('app/group', 'No pending requests.'),
                    'emptyTextOptions' => ['class' => 'text-sm text-gray-500'],
                ],
            ); ?>
        </div>
    </div>


</div>
</div>