<?php
use app\widgets\UserWidget;
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Group $model */
/** @var yii\data\ActiveDataProvider $userProvider */
/** @var app\models\search\UserSearch $userSearchModel */


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/admin', 'Groups'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



?>
<div class="group-view">
    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
        <?= Yii::t('app/admin', 'Group: {name}', ['name' => Html::encode($model->name)]) ?>
    </h1>
    <div class="flex justify-between gap-2 mb-4">
        <?php if ($model->deleted): ?>
            <?= Html::a(Yii::t('app/admin', 'Restore'), ['restore', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('app/admin', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app/admin', 'Are you sure you want to delete this group?'),
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::a(
            Yii::t('app/admin', 'Back'),
            ['index'],
            ['class' => 'self-end inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500']
        ) ?>
    </div>

    <?= DetailView::widget([
    'model' => $model,
    'options' => [
        'class' => 'mb-4'
        ],
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            [
                'attribute' => 'owner_id',
                'value' => Html::a($model->owner->username, Url::to(['/admin/user/view',
                    'id' => $model->owner_id
                ])),
                'format' => 'raw',
            ],
            [
                'label' => Yii::t('app/admin', 'Members Count'),
                'value' => $model->getMembers()->count(),
            ],
            [
                'label' => Yii::t('app/admin', 'Posts Count'),
                'value' => $model->getPosts()->count(),
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
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <?= UserWidget::widget([
            'title' => Yii::t('app/group', 'Group Members'),
            'itemButtonLabel' => function ($model) {
                    return Yii::t('app/admin', 'Show');
            },
            'itemButtonRoute' => function ($model) {
                return ['admin/user/view', 'id' => $model->user_id];
            },
            'itemView' => '_groupMember',
            'provider' => $userProvider,
            'ajax' => true,
            'emptyMessage' => Yii::t('app/group', 'You have no members yet.'),
            'searchModel' => $userSearchModel
    ]) ?>

</div>
