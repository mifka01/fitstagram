<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use yii\widgets\ListView;

$this->title = Yii::t('app', 'Posts');

?>
<div class="flex items-center justify-center">
    <div class="w-full sm:w-10/12 md:w-8/12 lg:w-5/12 flex flex-col">
        <?= ListView::widget([
            'dataProvider' => $postDataProvider,
            'itemView' => '_post',
            'itemOptions' => ['class' => 'item'],
            'options' => ['class' => 'space-y-16'],
            'summary' => '',
            'pager' => ['class' => \kop\y2sp\ScrollPager::class]
        ]); ?>
    </div>
</div>