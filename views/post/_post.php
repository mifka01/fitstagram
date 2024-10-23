<?php

/** @var yii\web\View $this */
/** @var app\models\Post $model */

?>

<!-- Post Header -->
<div class="flex flex-col space-y-4 border border-gray-200 rounded-lg p-4">
    <div class="flex justify-between">
        <div class="">
            <div class="flex items-center space-x-1">
                <p class="text-orange-600 text-lg"><?= $model->createdBy->username ?></p>

                <p class="text-gray-500 text-sm"><?= Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>
            </div>

        </div>
        <div class="flex items-center text-gray-500 text-sm space-x-1 me-3">
            <button><?= Yii::t('app', 'Hide') ?></button>
            <span>|</span>
            <button><?= Yii::t('app', 'Ban') ?></button>
            <svg class="w-4 h-4 stroke-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>

    </div>

    <div class="flex flex-col ">
        <!-- MediaFiles -->
        <?php foreach ($model->mediaFiles as $mediaFile): ?>
            <img class="rounded-lg" src="<?= $mediaFile->media_path ?>" alt="<?= $mediaFile->name ?>">
        <?php endforeach; ?>
        <div class="divide-y-2 divide-gray-200">
            <div class="px-2 py-2">
                <!-- Votes and Place line -->
                <div class="flex justify-between">
                    <!-- Votes -->
                    <div class="flex items-center mt-2">
                        <button class="flex items-center justify-center  ">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 text-orange-600">
                                <path d="m19.707 9.293-7-7a1 1 0 0 0-1.414 0l-7 7A1 1 0 0 0 5 11h3v10a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V11h3a1 1 0 0 0 .707-1.707z" class="fill-current stroke-black" data-name="Up" />
                            </svg>

                        </button>
                        <p class="mx-2"><?= $model->totalVotes ?></p>
                        <button class="flex items-center justify-center ">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6">
                                <path d="M19.924 13.617A1 1 0 0 0 19 13h-3V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v10H5a1 1 0 0 0-.707 1.707l7 7a1 1 0 0 0 1.414 0l7-7a1 1 0 0 0 .217-1.09z" class="fill-none stroke-black stroke-1" />
                            </svg>
                        </button>
                    </div>
                    <!-- Place -->
                    <div class="div text-gray-500 text-sm mt-2 justify-center">
                        <p class="text-gray-500 text-sm"><?= $model->place ?></p>
                    </div>
                </div>
                <!-- Description -->
                <p class="my-2 "><?= $model->description ?></p>
                <!-- Tags -->
                <?php foreach ($model->tags as $tag): ?>
                    <span class="text-gray-500 text-xs">#<?= $tag->name ?></span>
                <?php endforeach; ?>

            </div>
            <!-- Comments -->

            <div class="p-2">
                <?php foreach ($model->comments as $comment): ?>
                    <?= $this->render('_comment', ['comment' => $comment]) ?>
                <?php endforeach; ?>
            </div>
            <div class="p-2">
                <form action="">
                    <textarea class="w-full rounded-lg border-gray-500 resize-none overflow-hidden min-h-[3rem] focus:border-gray-600 focus:ring-gray-400" type="text" name="" id="" placeholder="<?= Yii::t('app/model', 'Leave a comment...') ?>" oninput="autoExpand(this)"></textarea>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    function autoExpand(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }
</script>
