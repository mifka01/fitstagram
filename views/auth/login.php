<?php
/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\forms\auth\LoginForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/auth', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Yii::t('app/auth', Html::encode($this->title)) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t("app/auth", "Please fill out the following fields to login")?>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <div class="space-y-6">
                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Enter your username')
                    ]) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Enter your password')
                    ]) ?>

                    <div class="flex items-center justify-between">
                        <?= $form->field($model, 'rememberMe')->checkbox([
                            'class' => 'h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500',
                            'labelOptions' => ['class' => 'font-medium text-gray-700']
                        ]) ?>
                    </div>

                    <div class="text-sm space-y-2">
                        <p class="text-gray-600">
                            <?= Yii::t('app/auth', 'If you forgot your password you can')?> 
                            <?= Html::a(
                                Yii::t('app/auth', 'reset it'),
                                ['auth/request-password-reset'],
                                ['class' => 'font-medium text-orange-600 hover:text-orange-500']
                            ) ?>.
                        </p>
                        <p class="text-gray-600">
                            <?= Yii::t('app/auth', 'Need a new verification email?') ?>
                            <?= Html::a(
                                Yii::t('app/auth', 'Resend'),
                                ['auth/resend-verification-email'],
                                ['class' => 'font-medium text-orange-600 hover:text-orange-500']
                            ) ?>
                        </p>
                        <p class="text-gray-600">
                            <?= Yii::t('app/auth', "Don't have an account?")?> 
                            <?= Html::a(
                                Yii::t('app/auth', 'Sign up'),
                                ['auth/signup'],
                                ['class' => 'font-medium text-orange-600 hover:text-orange-500']
                            ) ?>
                        </p>
                    </div>

                    <?= Html::submitButton(
                        Yii::t('app/auth', 'Login'),
                        ['class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out',
                        'name' => 'login-button']
                    ) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
