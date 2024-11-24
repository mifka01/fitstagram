<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var bool $showFilter */
/** @var bool $ajaxFilter */

use app\components\ScrollPager;
use app\widgets\SortWidget;
use yii\widgets\ListView;
use yii\widgets\Pjax;
?>


<?php if ($ajaxFilter): ?>
    <?php Pjax::begin([
        'formSelector' => false,
        'linkSelector' => '.sort-link',
    ]); ?>
<?php endif; ?>

<?php if ($showFilter): ?>
    <div class="flex justify-center space-x-4 mb-2">
        <?= SortWidget::widget([
            'sort' => $dataProvider->sort,
            'containerOptions' => ['class' => 'flex justify-center space-x-4 mb-2'],
            'activeLinkOptions' => ['class' => 'text-orange-600'],
            'allowBothWays' => false,
            'defaultSort' => 'new'
        ]) ?>
    </div>
<?php endif; ?>

<div id="carousel-wrapper" class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8 space-y-6">
    <?= ListView::widget([
        'id' => 'list-view-posts',
        'dataProvider' => $dataProvider,
        'itemView' => '_post',
        'itemOptions' => ['class' => 'item mb-4'],
        'layout' => "{items}\n{pager}",
        'summary' => '',
        'pager' => [
            'class' => ScrollPager::class,
            'pagination' => $dataProvider->getPagination(),
            'scrollOffset' => 1000,
            'id' => 'post-scroll-pager',
            'clientOptions' => [
                'throttleWait' => 50,
            ],
            'label' => Yii::t('app', 'Load More'),
        ],
        'emptyText' => '',
    ]); ?>

</div>

<?php if ($ajaxFilter): ?>
    <?php Pjax::end(); ?>
<?php endif; ?>