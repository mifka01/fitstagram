<?php
/**
 * @var yii\web\View $this
 * @var app\models\Comment $comment
 */
?>

<div class="">
    <div class="flex items-center space-x-2">
        <p class="text-orange-600 text-md"><?= $comment->createdBy->username ?></p>
        <p class="text-gray-500 text-sm mt-0"><?= Yii::$app->formatter->asRelativeTime($comment->created_at) ?></p>
    </div>
    <p class="mt-1"><?= $comment->content ?></p>
</div>
