<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\widgets\PostListWidget;

$this->title = Yii::t('app', 'Posts');

?>

<?= PostListWidget::widget([
    'dataProvider' => $postDataProvider,
]);


