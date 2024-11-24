<?php
use kartik\detail\DetailView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/admin', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
        <?= Yii::t('app/admin', 'User: {username}', ['username' => $model->username]) ?>
    </h1>

    <div class="flex justify-between gap-2 mb-4">
        <div>
        <?php if ($model->banned): ?>
            <?= Html::a(
                Yii::t('app/admin', 'Unban'),
                ['unban', 'id' => $model->id],
                ['class' => 'inline
        -flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500']
            ) ?>
        <?php else: ?>
            <?= Html::a(
                Yii::t('app/admin', 'Ban'),
                ['ban', 'id' => $model->id],
                ['class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500']
            ) ?>
        <?php endif; ?>

        <?php if ($model->deleted): ?>
            <?= Html::a(
                Yii::t('app/admin', 'Restore'),
                ['restore', 'id' => $model->id],
                ['class' => 'inline
        -flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500']
            ) ?>
        <?php else: ?>
            <?= Html::a(
                Yii::t('app/admin', 'Delete'),
                ['delete', 'id' => $model->id],
                [
                'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
                'data' => [
                    'confirm' => Yii::t('app/admin', 'Are you sure you want to delete this user?'),
                    'method' => 'post',
                ],
                ]
            ) ?>
        <?php endif; ?>
            <?php if (Yii::$app->authManager?->checkAccess($model->id, 'moderator')): ?>
                <?= Html::a(
                    Yii::t('app/admin', 'Remove Moderator'),
                    ['remove-moderator', 'id' => $model->id],
                    ['class' => 'inline-flex px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']
                ) ?>
            <?php else: ?>
                <?= Html::a(
                    Yii::t('app/admin', 'Make Moderator'),
                    ['make-moderator', 'id' => $model->id],
                    ['class' => 'inline-flex px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500']
                ) ?>
            <?php endif; ?>
        </div>
        <?= Html::a(
            Yii::t('app/admin', 'Back'),
            ['index'],
            ['class' => 'self-end inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500']
        ) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'username',
                'format' => 'raw',
                'value' => Html::encode($model->username),
            ],
            [
                'attribute' => 'email',
                'format' => 'email',
            ],
            [
                'attribute' => 'active',
                'format' => 'boolean',
            ],
            [
            'attribute' => 'deleted',
            'format' => 'boolean',
            'label' => Yii::t('app/admin', 'Deleted'),
            ],
            [
            'attribute' => 'banned',
            'format' => 'boolean',
            'label' => Yii::t('app/admin', 'Banned'),
            ],
            [
                'attribute' => 'show_activity',
                'format' => 'boolean',
            ],
            [
                'label' => Yii::t('app/admin', 'Posts'),
                'value' => $model->getPosts()->count(),
                'format' => 'raw',
            ],
            [
                'label' => Yii::t('app/admin', 'Comments'),
                'value' => $model->getComments()->count(),
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime'],
            ],
        ],
    ]) ?>
</div>
