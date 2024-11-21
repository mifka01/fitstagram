<?php
/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var bool $isGroupOwner */
/** @var int $countUsers */
/** @var int $countPosts */
/** @var string $joinedAt */
/** @var string $ownerUsername */
/** @var bool $isMember */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\components\ScrollPager;
use yii\helpers\Html;
use yii\widgets\ListView;

$this->title = Yii::t('app/group', 'Group {name}', ['name' => $model->name]);
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
                                    <?= Html::encode($model->name) ?>
                                </h1>
                                <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                                    <?php if ($joinedAt): ?>
                                        <span><?= Yii::t('app/user', 'Member since {date}', [
                                            'date' => Yii::$app->formatter->asDate($joinedAt)
                                        ]) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($isMember && !$isGroupOwner): ?>
                        <div class="flex flex-wrap justify-end self-stretch -m-2">
                            <?= Html::a(
                                Yii::t('app/group', 'Leave group'),
                                ['group-membership/leave-group', 'id' => $model->id],
                                ['class' => 'm-1 w-32 py-2 self-center text-center border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
                                'data' => ['method' => 'post']
                                ]
                            ) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="mt-6 grid grid-cols-3 gap-3 border-t border-gray-200 pt-4">
                    <div class="text-center">
                        <span class="text-xl font-bold text-gray-900">
                            <?= Yii::$app->formatter->asInteger($countUsers) ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/group', 'Users') ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <span class="text-xl font-bold text-gray-900">
                            <?= Yii::$app->formatter->asInteger($countPosts) ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/group', 'Posts') ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <a href="<?= Yii::$app->urlManager->createUrl(['/user/profile', 'username' => $ownerUsername]) ?>" class="text-xl font-bold text-gray-900">
                            <?= $ownerUsername ?>
                        </a>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/group', 'Owner') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?= ListView::widget([
        'id' => 'list-view-posts',
        'dataProvider' => $postDataProvider,
        'itemView' => '/post/_post',
        'itemOptions' => ['class' => 'item mb-4'],
        'layout' => "{items}\n{pager}",
        'summary' => '',
        'pager' => [
            'class' => ScrollPager::class,
            'pagination' => $postDataProvider->getPagination(),
            'scrollOffset' => 1000,
            'id' => 'post-scroll-pager',
            'clientOptions' => [
                'throttleWait' => 50,
            ],
            'label' => Yii::t('app', 'Load More'),
        ],
    ]); ?>
</div>
