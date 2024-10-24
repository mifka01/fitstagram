<?php
/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\forms\auth\SignupForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/auth', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app/auth', 'Please fill out the following fields to signup')?>
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-6 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <div class="space-y-4">
                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Choose your username')
                    ]) ?>

                    <?= $form->field($model, 'email')->textInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Enter your email address'),
                        'type' => 'email'
                    ]) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Create a password')
                    ]) ?>

                    <?= $form->field($model, 'password_repeat')->passwordInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Repeat a password')
                    ]) ?>

                    <div class="text-sm">
                        <p class="text-gray-600">
                            <?= Yii::t('app/auth', 'Already have an account?')?> 
                            <?= Html::a(
                                Yii::t('app/auth', 'Login'),
                                ['auth/login'],
                                ['class' => 'font-medium text-orange-600 hover:text-orange-500']
                            ) ?>
                        </p>
                    </div>

                    <?= Html::submitButton(
                        Yii::t('app/auth', 'Signup'),
                        ['class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out',
                        'name' => 'signup-button']
                    ) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
