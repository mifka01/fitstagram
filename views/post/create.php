<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\forms\PostForm $model */

use app\models\User;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/post', 'Create Post');
$this->params['breadcrumbs'][] = $this->title;

/** @var User|null $user */
$user = Yii::$app->user->isGuest ? null : Yii::$app->user->identity;
$groups = $user ? $user->getGroups()->active()->asArray()->all() : [];

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
        <div class="bg-white py-6 px-4 shadow sm:rounded-lg sm:px-10">

            <?php $form = ActiveForm::begin([
                'id' => 'post-form',
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>
            <div class="space-y-4">

                <?= $form->field($model, 'mediaFiles[]')->fileInput([
                    'multiple' => true,
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'accept' => 'image/*'
                ])->label(Yii::t('app/post', 'Upload Images')) ?>

                <?= $form->field($model, 'description')->textarea([
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/post', 'Enter post description'),
                    'rows' => 4
                ]) ?>

                <?= $form->field($model, 'tags')->widget(Select2::class, [
                    'maintainOrder' => true,
                    'showToggleAll' => false,
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => [
                        'value' => '',
                        'placeholder' => Yii::t('app/post', 'Select a tag ...'),
                        'multiple' => true,
                        'class' => 'form-control'
                    ],
                    'pluginOptions' => [
                        'autocomplete' => 'off',
                        'tags' => true,
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'maximumInputLength' => 20,
                        'maximumSelectionLength' => 20,
                        'maintainOrder' => true,
                        'ajax' => [
                            'url' => Url::to(['tag/list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(tag) { return tag.text; }'),
                        'templateSelection' => new JsExpression('function (tag) { return tag.text; }'),
                    ],
                ]) ?>

                <?= $form->field($model, 'group')->dropDownList(ArrayHelper::map($groups, 'id', 'name'), [
                    'prompt' => Yii::t('app/post', 'Select group...'),
                    'multiple' => false,
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                ]) ?>

                <?= $form->field($model, 'place')->textInput([
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm',
                    'placeholder' => Yii::t('app/post', 'Enter place')
                ]) ?>



                <?= $form->field($model, 'is_private', [
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
                                ' . $model->getAttributeLabel("is_private") . '
                            </span>
                        </label>',
                ])->checkbox([
                    'class' => 'sr-only peer',
                    'label' => false,
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