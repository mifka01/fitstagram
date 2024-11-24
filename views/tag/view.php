<?php

/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var int $countPosts */
/** @var string $ownerUsername */
/** @var string $lastUse */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\widgets\PostListWidget;
use yii\helpers\Html;

$this->title = Yii::t('app/tag', 'Tag {name}', ['name' => $model->name]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="carousel-wrapper" class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-start">
                    <div class="flex-1">
                        <div class="flex justify-between items-center space-x-4">
                            <div>
                                <h1 class="text-xl font-bold text-gray-900 line-clamp-1">
                                    <?= Yii::t('app/tag', 'Tag {name}', ['name' => $model->name]); ?>
                                </h1>
                                <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (Yii::$app->user->id && Yii::$app->authManager?->checkAccess(Yii::$app->user->id, 'moderator')): ?>
                        <div class="flex flex-col justify-end self-stretch -m-2 my-0">
                            <?= Html::a(
                                Yii::t('app/tag', 'Delete tag'),
                                ['tag/delete', 'id' => $model->id],
                                [
                                    'class' => 'm-1 w-32 py-2 self-center text-center border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
                                    'data' => ['method' => 'post']
                                ]
                            ) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-2 grid grid-cols-3 gap-3  border-t border-gray-200 pt-4">
                    <div class="text-center">
                        <span class="text-xl font-bold text-gray-900">
                            <?= $postDataProvider->getTotalCount() ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/tag', 'Posts') ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/user/profile', 'username' => $ownerUsername]) ?>" class="text-xl font-bold text-gray-900">
                            <?= $ownerUsername ?>
                        </a>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/tag', 'Created by') ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <span class="text-xl font-bold text-gray-900">
                            <?= Yii::$app->formatter->asRelativeTime($lastUse) ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/tag', 'Last use') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= PostListWidget::widget([
        'dataProvider' => $postDataProvider,
    ]) ?>

</div>