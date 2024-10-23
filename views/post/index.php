<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $postDataProvider */

use app\components\ScrollPager;
use yii\widgets\ListView;

$this->title = Yii::t('app', 'Posts');

?>
<div class="flex items-center justify-center">
    <div class="w-full sm:w-10/12 md:w-8/12 lg:w-5/12 flex flex-col">
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
</div>
