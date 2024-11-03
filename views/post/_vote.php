<?php

/** @var yii\web\View $this */
/** @var app\models\Post $model */

use app\models\forms\PostVoteForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$postVoteForm = new PostVoteForm([
    'id' => $model->id,
]);
$form = ActiveForm::begin([
    'id' => 'vote-form-' . $model->id,
    'action' => Url::to(['post/vote']),
    'method' => 'post',
    'options' => [
        'class' => 'flex items-center mt-2',
        'data-pjax' => true,
    ],
]); ?>

<?= $form->field($postVoteForm, 'id')->hiddenInput()->label(false) ?>

<button type="submit" name="<?= Html::getInputName($postVoteForm, 'type') ?>" value="1" class="flex items-center justify-center vote-button">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 <?= $model->currentUserVote && $model->currentUserVote->up == 1 ? 'text-orange-600' : 'text-gray-50' ?>">
        <path d="m19.707 9.293-7-7a1 1 0 0 0-1.414 0l-7 7A1 1 0 0 0 5 11h3v10a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V11h3a1 1 0 0 0 .707-1.707z" class="fill-current stroke-black stroke-1" />
    </svg>
</button>

<p class="mx-2"><?= $model->totalVotes ?></p>

<button type="submit" name="<?= Html::getInputName($postVoteForm, 'type') ?>" value="0" class="flex items-center justify-center vote-button">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-6 w-6 <?= $model->currentUserVote && $model->currentUserVote->up == 0 ? 'text-orange-600' : 'text-gray-50' ?>">
        <path d="M19.924 13.617A1 1 0 0 0 19 13h-3V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v10H5a1 1 0 0 0-.707 1.707l7 7a1 1 0 0 0 1.414 0l7-7a1 1 0 0 0 .217-1.09z" class="fill-current stroke-black stroke-1" />
    </svg>
</button>

<?php ActiveForm::end(); ?>