<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\forms\auth\LoginForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-login">
    <h1 class="text-3xl font-semibold"><?= Yii::t('app/auth', Html::encode($this->title)) ?></h1>

    <p class="mt-2"><?= Yii::t("app/auth", "Please fill out the following fields to login:")?></p>

    <div class="flex justify-center mt-6">
        <div class="w-full max-w-md">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) ?>

                <?= $form->field($model, 'password')->passwordInput(['class' => 'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm']) ?>

                <?= $form->field($model, 'rememberMe')->checkbox(['class' => 'h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500']) ?>

                <div class="text-sm text-gray-500 mt-2">
                    <?= Yii::t('app/auth', 'If you forgot your password you can')?> 
                    <?= Html::a(Yii::t('app/auth', 'reset it'), ['auth/request-password-reset'], ['class' => 'text-indigo-600 hover:text-indigo-500']) ?>.
                    <br>
                    <?= Yii::t('app/auth', 'Need a new verification email?') ?>
                    <?= Html::a(Yii::t('app/auth', 'Resend'), ['auth/resend-verification-email'], ['class' => 'text-indigo-600 hover:text-indigo-500']) ?>
                    <br>
                    <?= Yii::t('app/auth', "Don't have an account?")?> 
                    <?= Html::a(Yii::t('app/auth', 'Sign up'), ['auth/signup'], ['class' => 'text-indigo-600 hover:text-indigo-500']) ?>
                </div>

                <div class="mt-6">
                    <?= Html::submitButton(Yii::t('app/auth', 'Login'), ['class' => 'w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

