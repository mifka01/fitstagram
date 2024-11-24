<?php

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var bool $isOwnProfile */
/** @var yii\data\ActiveDataProvider $ownedGroupsProvider */
/** @var yii\base\Model|false $ownedSearchModel */
/** @var yii\data\ActiveDataProvider $joinedGroupsProvider */
/** @var yii\base\Model|false $joinedSearchModel */
/** @var int $countOwnedGroups */
/** @var int $countJoinedGroups */
/** @var int $countPosts */
/** @var app\models\User $currentUser */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\widgets\GroupWidget;
use app\widgets\PostListWidget;
use yii\helpers\Html;

$this->title = Yii::t('app/user', '{username}\'s Profile', ['username' => $model->username]);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6 mb-5">
    <div class="sm:mx-auto sm:w-full sm:max-w-3xl">
        <div class="bg-white shadow sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-start">
                    <div class="flex-1">
                        <div class="flex justify-between items-center space-x-4">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">
                                    <?= Html::encode($model->username) ?>
                                </h1>
                                <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                                    <span><?= Yii::t('app/user', 'Member since {date}', [
                                                'date' => Yii::$app->formatter->asDate($model->created_at)
                                            ]) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($isOwnProfile): ?>
                        <div class="flex flex-wrap justify-end self-stretch -m-2">
                            <?= Html::a(
                                Yii::t('app/user', 'Friends'),
                                ['user/permitted-users'],
                                ['class' => 'm-1 w-32 py-2 self-center text-center border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
                            ) ?>
                            <?= Html::a(
                                Yii::t('app/user', 'Edit Profile'),
                                ['user/update'],
                                ['class' => 'm-1 w-32 py-2 self-center text-center border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
                            ) ?>
                            <?= Html::a(
                                Yii::t('app/auth', 'Logout'),
                                ['auth/logout'],
                                [
                                    'class' => 'm-1 w-32 py-2 self-center text-center border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
                                    'data' => ['method' => 'post']
                                ]
                            ) ?>
                        </div>
                    <?php else: ?>
                        <div class="flex flex-wrap justify-end self-stretch -m-2">
                            <?php if ($currentUser != null && $currentUser->isPermittedUser($model->id)): ?>
                                <?= Html::a(
                                    Yii::t('app/user', 'Remove Friend'),
                                    ['user/revoke-permitted-user', 'id' => $model->id],
                                    ['class' => 'm-1 w-32 py-2 self-center text-center border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',]
                                ) ?>
                            <?php else: ?>
                                <?= Html::a(
                                    Yii::t('app/user', 'Add Friend'),
                                    ['user/add-permitted-user', 'id' => $model->id],
                                    ['class' => 'm-1 w-32 py-2 self-center text-center border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
                                ) ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-4 border-t border-gray-200 pt-4">
                    <div class="text-center">
                        <span class="text-2xl font-bold text-gray-900">
                            <?= Yii::$app->formatter->asInteger($countOwnedGroups) ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/group', 'Created Groups') ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <span class="text-2xl font-bold text-gray-900">
                            <?= Yii::$app->formatter->asInteger($countJoinedGroups) ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/group', 'Joined Groups') ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <span class="text-2xl font-bold text-gray-900">
                            <?= Yii::$app->formatter->asInteger($countPosts) ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/model', 'Posts') ?>
                        </span>
                    </div>
                    <div class="text-center">
                        <span class="text-2xl font-bold text-gray-900">
                            <?= $model->show_activity ? Yii::$app->formatter->asRelativeTime($model->lastActiveAt) : Yii::t('app/model', 'Hidden') ?>
                        </span>
                        <span class="block text-sm text-gray-500">
                            <?= Yii::t('app/user', 'Last Active') ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php if ($countOwnedGroups > 0 || $countJoinedGroups > 0): ?>
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6 space-y-4">
                        <?php if ($countOwnedGroups > 0): ?>
                            <?= GroupWidget::widget([
                                'title' => Yii::t('app/group', 'Created Groups'),
                                'itemButtonLabel' => $currentUser != null ? Yii::t('app/group', 'View') : false,
                                'itemButtonRoute' => $currentUser != null ? function ($model) {
                                    return ['group/view', 'id' => $model->id];
                                } : false,
                                'linkOnTitle' => true,
                                'provider' => $ownedGroupsProvider,
                                'ajax' => true,
                                'emptyMessage' => Yii::t('app/group', 'This user has not created any groups yet.'),
                                'searchModel' => $ownedSearchModel
                            ]) ?>
                        <?php endif; ?>

                        <?php if ($countJoinedGroups > 0): ?>
                            <?= GroupWidget::widget([
                                'title' => Yii::t('app/group', 'Joined Groups'),
                                'itemButtonLabel' => Yii::t('app/group', 'View'),
                                'itemButtonRoute' => function ($model) {
                                    return ['group/view', 'id' => $model->id];
                                },
                                'linkOnTitle' => true,
                                'provider' => $joinedGroupsProvider,
                                'ajax' => true,
                                'emptyMessage' => Yii::t('app/group', 'This user has not joined any groups yet.'),
                                'searchModel' => $joinedSearchModel
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= PostListWidget::widget([
        'dataProvider' => $postDataProvider,
    ]) ?>