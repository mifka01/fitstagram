<?php

/**
 * @var yii\web\View $this
 * @var app\models\Comment $model
 */
?>

<div class="">
    <div class="flex items-center space-x-2">
        <p class="text-orange-600 text-md">
            <a href="<?= \yii\helpers\Url::to(['/user/profile', 'username' => $model->createdBy->username]) ?>">
                <?= $model->createdBy->username ?>
            </a>
        </p>
        <p class="text-gray-500 text-sm mt-0"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>
    </div>
    <p class="mt-1"><?= $model->content ?></p>
</div>