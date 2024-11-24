<?php

use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Post $model */

$this->title = Yii::t('app/admin', 'Post: {id}', ['id' => strval($model->id)]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/admin', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$images = '<div class="flex">';
foreach ($model->mediaFiles as $image) {
    $images .= Html::img('/'. $image->path, ['class' => 'w-1/4']);
}
$images .= '</div>';

?>
<div class="post-view">

    <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">
        <?=Yii::t('app/admin', 'Post: {id}', ['id' =>  $model->id]) ?>
    </h1>

    <div class="flex justify-between gap-2 mb-4">
        <?php if ($model->deleted): ?>
            <?= Html::a(Yii::t('app/admin', 'Restore'), ['restore', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php else: ?>
            <?= Html::a(Yii::t('app/admin', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app/admin', 'Are you sure you want to delete this post?'),
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
        'attributes' => [
            'id',
            [
            'attribute' => 'created_by',
                'value' => Html::a($model->createdBy->username, Url::to(['/admin/post/view',
                'id' => $model->createdBy->id
            ])),
                'format' => 'raw',
            ],
            ['label' => Yii::t('app/admin', 'Images'),
            'value' => $images,
            'format' => 'html',
            ],
            'is_private:boolean',
            [
            'attribute' => 'group',
            'label' => Yii::t('app/admin', 'Group'),
            'value' => $model->group ? Html::a($model->group->name, Url::to(['/admin/group/view', 'id' => $model->group->id])) : Yii::t('app/admin', 'No group'),
            'format' => 'raw',
            ],
            'upvote_count',
            'downvote_count',
            [
            'label' => Yii::t('app/admin', 'Comments'),
            'value' => $model->getComments()->count(),
            ],
            [
            'label' => Yii::t('app/admin', 'Tags'),
            'value' => implode(', ', ArrayHelper::getColumn($model->tags, 'name')),
            ],
            'description:ntext',
            'place',
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

</div>
