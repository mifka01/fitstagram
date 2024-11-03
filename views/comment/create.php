<?php

/**
 * @var yii\widgets\ActiveForm $form
 * @var app\models\forms\CommentForm $model
 * @var app\models\Comment|null $newComment
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'comment-form-' . $model->postId,
    'action' => ['comment/create'],
    'options' => [
        'data-pjax' => true,
        'class' => 'border rounded-lg border-gray-200 flex mt-2',
    ],
    'errorCssClass' => '',
]);

$js = <<<JS
function autoExpand(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
}
JS;
Yii::$app->view->registerJs($js, \yii\web\View::POS_END);
?>

<?= $form->field($model, 'postId')->hiddenInput()->label(false) ?>


<?= $form->field($model, 'content', [
    'options' => ['tag' => false],
    'template' => "{input}\n{error}",
])->textarea([
    'class' => 'pt-3 pb-0 flex-grow border-0 bg-transparent w-full resize-none focus:ring-0 focus:outline-none text-sm',
    'placeholder' => Yii::t('app/model', 'Leave a comment...'),
    'oninput' => 'autoExpand(this)',
    'maxlength' => 512,
])->label(false) ?>

<?= Html::submitButton(Yii::t('app/model', 'Send'), [
    'class' => ' text-orange-600 right-0 pb-1 mx-4 text-sm',
    'options' => [
        'data-pjax' => true,
    ],
]) ?>


<?php ActiveForm::end();
