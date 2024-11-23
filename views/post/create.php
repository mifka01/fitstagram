<?php

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var \app\models\forms\PostForm $model */

use app\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/post', 'Create Post');
$this->params['breadcrumbs'][] = $this->title;

/** @var User|null $user */

$js = <<<JS
function autoExpand(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
}
$(document).ready(function() {
        const groupSelect = $('#postform-group')
        const privateToggle = document.getElementById('postform-is_private');
        const privateToggleContainer = document.getElementById('privacy-toggle-container');
        const groupContainer = document.getElementById('group-container');
        const sharingTypeContainer = document.getElementById('sharing-type-container');
        const publicOption = document.getElementById('sharing-public');
        const privateOption = document.getElementById('sharing-private');
        const groupOption = document.getElementById('sharing-group');

        function updateFormState(sharingType) {
            switch(sharingType) {
                case 'public':
                    groupContainer.style.display = 'none';
                    privateToggleContainer.style.display = 'none';
                    groupSelect.val(null).trigger('change');
                    privateToggle.checked = false;
                    break;
                case 'private':
                    groupContainer.style.display = 'none';
                    privateToggleContainer.style.display = 'none';
                    groupSelect.val(null).trigger('change')
                    privateToggle.checked = true;
                    break;
                case 'group':
                    groupContainer.style.display = 'block';
                    privateToggleContainer.style.display = 'none';
                    privateToggle.checked = false;
                    break;
            }
        }

        document.querySelectorAll('.sharing-type').forEach(radio => {
            radio.addEventListener('change', (e) => {
                updateFormState(e.target.value);
            });
        });

        const initialSharingType = document.querySelector('input[name="sharing-type"]:checked')?.value || 'public';
        updateFormState(initialSharingType);
});

function handleFileSelect(input) {
    const fileList = document.getElementById('file-list');
    const fileCounter = document.getElementById('file-counter');

    fileList.innerHTML = '';

    if (input.files.length > 0) {
        for (const file of input.files) {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md';

            const fileInfo = document.createElement('div');
            fileInfo.className = 'flex items-center';

            const fileIcon = document.createElement('svg');
            fileIcon.className = 'h-5 w-5 text-gray-400 mr-2';
            fileIcon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';

            const fileName = document.createElement('span');
            fileName.className = 'text-sm text-gray-700';
            fileName.textContent = file.name;

            fileInfo.appendChild(fileIcon);
            fileInfo.appendChild(fileName);
            fileItem.appendChild(fileInfo);

            fileList.appendChild(fileItem);
        }

        const fileCount = input.files.length;
    }
}
JS;

$this->registerJs($js, \yii\web\View::POS_END);
?>


<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                <?= Yii::t('app/post', Html::encode($this->title)) ?>
            </h1>
            <p class="mt-3 text-lg text-gray-600">
                <?= Yii::t("app/post", "Share your moments with the community") ?>
            </p>
        </div>

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <?php $form = ActiveForm::begin([
                'id' => 'post-form',
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'class' => 'space-y-6 px-6 pb-6 sm:px-8'
                ],
            ]); ?>
            <div class="space-y-2">
                <div class="required">
                    <label class="block text-sm font-medium text-gray-700">
                    <?= Yii::t('app/post', 'Upload Images') ?>
                    </label>
                </div>
                <div class="mt-1">
                    <div class="flex items-center justify-center">
                        <label for="postform-mediafiles" class="cursor-pointer w-full">
                            <div class="flex flex-col items-center justify-center p-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-400 transition-colors duration-200 bg-white">
                                <svg class="w-16 h-16 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?= Yii::t('app/post', 'Select Images...') ?>
                                <?= $form->field($model, 'mediaFiles[]', [
                                    'template' => "{input}\n{error}",
                                    'options' => ['class' => '']
                                ])->fileInput([
                                    'multiple' => true,
                                    'class' => 'sr-only',
                                    'accept' => 'image/*',
                                    'onchange' => 'handleFileSelect(this)'
                                ]) ?>
                            </div>
                        </label>
                    </div>
                    <div id="file-list" class="mt-4 space-y-2"></div>
                    <div id="file-counter" class="mt-2 text-sm text-gray-500"></div>
                </div>
            </div>
            <?= $form->field($model, 'description', [
                'options' => ['class' => 'space-y-2']
            ])->textarea([
                                            'class' => 'block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition duration-200',
                                            'placeholder' => Yii::t('app/post', 'Share your thoughts...'),
                                            'rows' => 4,
                                            'oninput' => 'autoExpand(this)',
            ])->label(Yii::t('app/post', 'Description'), ['class' => 'block text-sm font-medium text-gray-700']) ?>

            <?= $form->field($model, 'tags', [
                'options' => ['class' => 'space-y-2']
            ])->widget(Select2::class, [
                'maintainOrder' => true,
                'showToggleAll' => false,
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'placeholder' => Yii::t('app/post', 'Add tags...'),
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
            ])->label(Yii::t('app/post', 'Tags'), ['class' => 'block text-sm font-medium text-gray-700']) ?>

            <div id="sharing-type-container" class="space-y-4">
                <div class='required'>
                <label class="block text-sm font-medium text-gray-700">
                    <?= Yii::t('app/post', 'Sharing Options') ?>
                </label>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="radio" id="sharing-public" name="sharing-type" value="public" class="sharing-type h-4 w-4 text-orange-600 focus:ring-orange-500" checked>
                        <label for="sharing-public" class="ml-3 block text-sm font-medium text-gray-700">
                            <?= Yii::t('app/post', 'Public - Share with everyone') ?>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="sharing-private" name="sharing-type" value="private" class="sharing-type h-4 w-4 text-orange-600 focus:ring-orange-500">
                        <label for="sharing-private" class="ml-3 block text-sm font-medium text-gray-700">
                            <?= Yii::t('app/post', 'Private - Share with your friends') ?>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio" id="sharing-group" name="sharing-type" value="group" class="sharing-type h-4 w-4 text-orange-600 focus:ring-orange-500">
                        <label for="sharing-group" class="ml-3 block text-sm font-medium text-gray-700">
                            <?= Yii::t('app/post', 'Group - Share with group members') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div id="group-container" class="space-y-2 required" style="display:none;">

            <?= $form->field($model, 'group', [
                'options' => ['class' => 'space-y-2']
            ])->widget(Select2::class, [
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'placeholder' => Yii::t('app/post', 'Select a group...'),
                    'class' => 'form-control'
                ],
                'pluginOptions' => [
                    'autocomplete' => 'off',
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'maximumInputLength' => 20,
                    'maximumSelectionLength' => 20,
                    'maintainOrder' => true,
                    'ajax' => [
                        'url' => Url::to(['group/list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(tag) { return tag.text; }'),
                    'templateSelection' => new JsExpression('function (tag) { return tag.text; }'),
                ],
                ])->label(Yii::t('app/post', 'Group'), ['class' => 'block text-sm font-medium text-gray-700']) ?>

            </div>

            <div id="privacy-toggle-container" style="display: none;">
                <?= $form->field($model, 'is_private', [
                    'template' => '{input}',
                    'options' => ['class' => '']
                ])->checkbox([
                    'class' => 'hidden',
                    'label' => false,
                ]) ?>
            </div>

            <?= $form->field($model, 'place', [
                'options' => ['class' => 'space-y-2']
            ])->textInput([
                'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm transition duration-200',
                'placeholder' => Yii::t('app/post', 'Enter location')
            ])->label(Yii::t('app/post', 'Location'), ['class' => 'block text-sm font-medium text-gray-700']) ?>

            <div class="pt-6">
                <?= Html::submitButton(
                    Yii::t('app/post', 'Create Post'),
                    [
                        'class' => 'w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-200 ease-in-out',
                        'name' => 'create-post-button'
                    ]
                ) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
