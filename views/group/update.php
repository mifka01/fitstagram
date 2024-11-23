<?php
/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\forms\GroupForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/group', 'Edit Group');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Yii::t('app/group', Html::encode($this->title)) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t("app/group", "Edit the following fields to update the group")?>
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $form = ActiveForm::begin(['id' => 'group-form']); ?>
                <div class="space-y-6">
                    <?= $form->field($model, 'name')->textInput([
                        'autofocus' => true,
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/group', 'Enter group name')
                    ]) ?>

                    <?= $form->field($model, 'description')->textarea([
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'placeholder' => Yii::t('app/group', 'Enter group description'),
                        'rows' => 4
                    ]) ?>

                    <?= Html::submitButton(
                        Yii::t('app/group', 'Edit Group'),
                        ['class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out',
                        'name' => 'create-group-button']
                    ) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
