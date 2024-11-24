<?php

/** @var yii\web\View $this */
/** @var app\models\Post $model */

use app\models\forms\CommentForm;
use app\widgets\CarouselWidget;
use app\widgets\PostCommentListView;
use yii\helpers\Html;

$js = <<<JS
$(document).ready(function() {
    // Initial setup for toggle buttons
    setupToggleButtons();

    const observer = new MutationObserver(() => {
        setupToggleButtons();
    });

    observer.observe(document.querySelector("#list-view-posts"), {
        childList: true,
    });
});

// Function to set up toggle buttons
function setupToggleButtons() {
    $(document).ready(function() {
        $('.toggleButton').each(function() {
            if (!$(this).data('listener-added')) {
                $(this).click(function() {
                    $(this).siblings('.tags').toggleClass('hidden');   
                    $(this).siblings('.description').toggleClass('line-clamp-1');
                    $(this).toggleClass('hidden');
                    $(this).siblings('.toggleButton').toggleClass('hidden');
                });
                $(this).data('listener-added', true);
            }
        });
    });
}

function autoExpand(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
}
JS;

$this->registerJs($js);
?>

<!-- Post Header -->
<div class="sm:mx-auto sm:w-full sm:max-w-xl">
    <div class="flex flex-col space-y-4 border border-gray-200 rounded-lg p-4">
        <div class="flex justify-between">
            <div class="">
                <div class="flex items-center space-x-1">
                    <a href="<?= Yii::$app->urlManager->createUrl(['/user/profile', 'username' => $model->createdBy->username]) ?>" class="text-orange-600 text-lg">
                        <?= $model->createdBy->username ?>
                    </a>
                    <p class="text-gray-500 text-sm"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>
                </div>
                <?php if ($model->group): ?>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/group/view', 'id' => $model->group->id]) ?>" class="text-gray-500 text-sm line-clamp-1" title="<?= $model->group->name ?>">
                        <?= $model->group->name ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="flex items-center text-gray-500 text-sm space-x-1 me-3">
                <?= Html::a(
                    Yii::t('app', 'Delete'),
                    ['post/delete', 'id' => $model->id],
                ) ?>
                <span>|</span>
                <?= Html::a(
                    Yii::t('app', 'Ban'),
                    ['user/ban', 'id' => $model->createdBy->id],
                ) ?>
                <?php if ($model->is_private): ?>
                    <svg class="w-4 h-4 stroke-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                <?php endif; ?>
            </div>

        </div>
        <div class="flex flex-col">
            <!-- MediaFiles -->
            <?= CarouselWidget::widget([
                'mediaFiles' => $model->mediaFiles,
            ]) ?>
            <div class="divide-y-2 divide-gray-200">
                <div class="post-detail px-2 py-2">
                    <!-- Votes and Place line -->
                    <div class="flex justify-between">
                        <?= $this->render('/post/_vote', [
                            'model' => $model
                        ]) ?>

                        <div class="div text-gray-500 text-sm mt-2 justify-center">
                            <p class="text-gray-500 text-sm"><?= $model->place ?></p>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="my-2">
                        <p class="line-clamp-1 overflow-hidden text-ellipsis description">
                            <?= $model->description ?>
                        </p>
                        <!-- Tags -->
                        <div class="<?= empty($model->description) ? '' : 'hidden' ?> tags mb-2">
                            <?php foreach ($model->tags as $tag): ?>
                                <span class="text-gray-500 text-xs"><?= Html::a("#" . $tag->name, ['tag/view', 'id' => $tag->id],) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($model->description)): ?>
                            <button class="toggleButton text-gray-500 text-sm">
                                <?= Yii::t('app/post', 'Show more') ?>
                            </button>
                            <button class="hidden toggleButton text-gray-500 text-sm">
                                <?= Yii::t('app/post', 'Show less') ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Comments -->
                <div class="comments px-2 py-2">
                    <?= PostCommentListView::widget([
                        'post' => $model,
                    ]) ?>

                    <?= $this->render('/comment/create', [
                        'model' => new CommentForm(['postId' => $model->id]),
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>