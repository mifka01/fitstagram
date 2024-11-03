<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\components\ScrollPager;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Posts');

?>
<div id="carousel-wrapper" class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <?= ListView::widget([
        'id' => 'list-view-posts',
        'dataProvider' => $postDataProvider,
        'itemView' => '_post',
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
