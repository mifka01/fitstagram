<?php

/**
 * @var yii\web\View $this
 * @var app\models\Comment $comment
 */
?>

<div class="">
    <div class="flex items-center space-x-2">
        <p class="text-orange-600 text-md">
            <a href="<?= \yii\helpers\Url::to(['/user/profile', 'username' => $comment->createdBy->username]) ?>">
                <?= $comment->createdBy->username ?>
            </a>
        </p>
        <p class="text-gray-500 text-sm mt-0"><?= Yii::$app->formatter->asRelativeTime($comment->created_at) ?></p>
    </div>
    <p class="mt-1"><?= $comment->content ?></p>
</div>