<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\components\ScrollPager;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Posts');

?>
<div id="carousel-wrapper" class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <?= ListView::widget([
        'dataProvider' => $postDataProvider,
        'itemView' => '_post',
        'itemOptions' => ['class' => 'item mb-4'],
        'layout' => "{items}",
        'summary' => '',
    ]); ?>

    <?= ScrollPager::widget([
        'pagination' => $postDataProvider->getPagination(),
        'scrollOffset' => 1000,
        'label' => Yii::t('app', 'Load More'),
        'clientOptions' => [
            'throttleWait' => 50,
        ],
    ]) ?>
</div>
