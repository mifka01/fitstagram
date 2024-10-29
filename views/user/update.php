<?php
/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\User $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/model', 'Edit Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t('app/auth', 'Update your profile information')?>
        </p>
    </div>
    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-6 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $form = ActiveForm::begin(['id' => 'form-profile']); ?>
                <div class="space-y-4">
                    <?= $form->field($model, 'username')->textInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Your username')
                    ]) ?>

                    <?= $form->field($model, 'email')->textInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Your email address'),
                        'type' => 'email'
                    ]) ?>

                    <?= $form->field($model, 'new_password')->passwordInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Leave empty to keep current')
                    ]) ?>

                    <?= $form->field($model, 'new_password_repeat')->passwordInput([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/auth', 'Repeat new password')
                ]) ?>
                <?= $form->field($model, 'show_activity', [
                'template' => '
                <label class="inline-flex items-center cursor-pointer">
                    {input}
                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 
                rounded-full peer peer-checked:after:translate-x-full 
                rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white 
                after:content-[\'\'] after:absolute after:top-[2px] after:start-[2px] 
                after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5
                after:transition-all peer-checked:bg-orange-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-900">
                        '.$model->getAttributeLabel("show_activity").'
                    </span>
                </label>',
                    ])->checkbox([
                'class' => 'sr-only peer',
                'label' => false,
                ]) ?>


                    <?= Html::submitButton(
                        Yii::t('app/auth', 'Save Changes'),
                        ['class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out',
                        'name' => 'profile-button']
                    ) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
