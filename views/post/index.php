<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use yii\widgets\ListView;

$this->title = Yii::t('app', 'Posts');

echo $postDataProvider->getTotalCount() . ' ' . Yii::t('app', 'Posts') . '<br>';

echo ListView::widget([
    'dataProvider' => $postDataProvider,
    'itemView' => '_post',
    'itemOptions' => ['class' => 'item'],
    'pager' => ['class' => \kop\y2sp\ScrollPager::class]
]);
