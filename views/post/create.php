<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\forms\PostForm $model */

use app\models\Group;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/post', 'Create Post');
$this->params['breadcrumbs'][] = $this->title;
$groups = Group::find()
    ->joinWith('members')
    ->where(['user.id' => Yii::$app->user->id])
    ->select(['group.name', 'group.id'])
    ->indexBy('id')
    ->column();

?>
<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900">
            <?= Yii::t('app/post', Html::encode($this->title)) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600">
            <?= Yii::t("app/post", "Please fill out the following fields to create a post") ?>
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <?php $form = ActiveForm::begin(['id' => 'post-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="space-y-6">

                <?= $form->field($model, 'mediaFiles[]')->fileInput(
                    [
                        'multiple' => true,
                        'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                        'accept' => 'image/*'
                    ]
                )->label(Yii::t('app/post', 'Upload Images*')) ?>

                <?= $form->field($model, 'description')->textarea([
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/post', 'Enter post description'),
                    'rows' => 4
                ]) ?>

                <?= $form->field($model, 'tags')->textInput([
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/post', 'Enter tags (space separated)')
                ]) ?>

                <?= $form->field($model, 'group')->dropDownList($groups, [
                    'prompt' => Yii::t('app/post', 'Select group...'),
                    'multiple' => false,
                    'class' => 'form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                ]) ?>

                <?= $form->field($model, 'place')->textInput([
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/post', 'Enter place')
                ]) ?>

                <?= $form->field($model, 'is_private')->checkbox([
                    'class' => 'h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500',
                    'labelOptions' => ['class' => 'font-medium text-gray-700']
                ]) ?>

                <?= Html::submitButton(
                    Yii::t('app/post', 'Create new Post'),
                    [
                        'class' => 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out',
                        'name' => 'create-post-button'
                    ]
                ) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>