<?php
/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\forms\ContactForm $model */

use app\components\ReCaptcha3;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/model', 'Contact');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app/model', 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.') ?>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                <div class="space-y-6">
                    <?= $form->field($model, 'name')->textInput([
                        'autofocus' => true,
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/model', 'Enter your name')
                    ]) ?>

                    <?= $form->field($model, 'email')->textInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/model', 'Enter your email')
                    ]) ?>

                    <?= $form->field($model, 'subject')->textInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/model', 'Enter subject')
                    ]) ?>

                    <?= $form->field($model, 'body')->textarea([
                        'rows' => 4,
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/model', 'Enter your message')
                    ]) ?>

                    <?= $form->field($model, 'reCaptcha', [
                        'enableAjaxValidation' => false,
                    ])->widget(ReCaptcha3::class)->label(false) ?>


                    <?= Html::submitButton(
                        Yii::t('app/model', 'Send Message'),
                        ['class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out',
                        'name' => 'contact-button']
                    ) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
