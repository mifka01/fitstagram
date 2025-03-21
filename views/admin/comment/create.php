<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Comment $model */

$this->title = Yii::t('app/admin', 'Create Comment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/admin', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
