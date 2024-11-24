<?php

/**
 * @var yii\web\View $this
 * @var app\models\Comment $model
 */
?>

<div class="relative">
    <div class="flex items-center space-x-2">
        <p class="text-orange-600 text-md">
            <a href="<?= \yii\helpers\Url::to(['/user/profile', 'username' => $model->createdBy->username]) ?>">
                <?= $model->createdBy->username ?>
            </a>
        </p>
        <p class="text-gray-500 text-sm mt-0"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>
    </div>
    <p class="mt-1"><?= $model->content ?></p>

    <!-- Trash Can Icon -->
    <?php if (Yii::$app->user->id && Yii::$app->authManager?->checkAccess(Yii::$app->user->id, 'deleteComment', ['commentId' => $model->id])): ?>
        <a href="<?= \yii\helpers\Url::to(['/comment/delete', 'id' => $model->id]) ?>" class="absolute top-0 right-0 text-gray-600 hover:text-red-600 text-xs" data-method="post">
            <i class="fas fa-trash-alt"></i>
        </a>
    <?php endif; ?>
</div>