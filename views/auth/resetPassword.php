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
<div class="bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Yii::t('app/auth', Html::encode($this->title)) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app', 'Please choose your new password:') ?>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                <div class="space-y-6">
                    <?= $form->field($model, 'password')->passwordInput([
                        'autofocus' => true,
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => 'Enter your new password'
                    ]) ?>

                    <div class="text-sm space-y-1">
                        <p class="text-gray-600">
                            Password should:
                        </p>
                        <ul class="text-gray-500 list-disc list-inside space-y-1">
                            <li>Be at least 8 characters long</li>
                            <li>Include at least one number</li>
                            <li>Include at least one special character</li>
                        </ul>
                    </div>

                    <div class="text-sm">
                        <p class="text-gray-600">
                            <?= Yii::t('app/auth', 'Remember your password?')?> 
                            <?= Html::a(
                                Yii::t('app/auth', 'Login here'),
                                ['auth/login'],
                                ['class' => 'font-medium text-orange-600 hover:text-orange-500']
                            ) ?>
                        </p>
                    </div>

                    <?= Html::submitButton(
                        Yii::t('app/auth', 'Save'),
                        ['class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out']
                    ) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
