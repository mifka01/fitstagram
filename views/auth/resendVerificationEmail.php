<?php

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \app\models\forms\auth\ResetPasswordForm $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Resend verification email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-resend-verification-email">
    <h1 class="text-3xl font-semibold"><?= Yii::t('app/auth', Html::encode($this->title)) ?></h1>

    <p class="mt-2"><?= Yii::t('app/auth', 'Please fill out your email. A verification email will be sent there.') ?></p>

    <div class="flex justify-center mt-6">
        <div class="w-full max-w-md">
            <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) ?>

            <div class="mt-6">
                <?= Html::submitButton(Yii::t('app/auth', 'Send'), ['class' => 'w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

