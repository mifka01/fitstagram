<?php

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \app\models\forms\auth\ResetPasswordForm $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1 class="text-3xl font-semibold"><?= Yii::t('app/auth', Html::encode($this->title)) ?></h1>

    <p class="mt-2"><?= Yii::t('app', 'Please choose your new password:') ?></p>

    <div class="flex justify-center mt-6">
        <div class="w-full max-w-md">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true, 'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) ?>

                <div class="mt-6">
                    <?= Html::submitButton(Yii::t('app/auth', 'Save'), ['class' => 'w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

