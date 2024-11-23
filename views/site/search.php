<?php
use kazda01\search\SearchInput;
use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = Yii::t('app', 'Search');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-gray-50 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <h1 class="text-center text-3xl font-bold tracking-tight text-gray-900 mb-2">
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="mt-2 text-center text-sm text-gray-600 mb-6">
            <?= Yii::t('app', "Find what you're looking for.") ?>
        </p>
        
            <div class="max-w-xl mx-auto">
                <?= SearchInput::widget([
                    'search_id' => 'main-search',
                    'placeholder' => Yii::t('app', 'Type to search...'),
                    'wrapperClass' => 'flex flex-col justify-center',
                    'formClass' => 'relative',
                    'inputClass' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm h-12 pl-4 pr-12',
                    'buttonClass' => 'absolute inset-y-0 right-0 flex items-center px-4 text-gray-500 hover:text-orange-600 focus:outline-none transition duration-150 ease-in-out'
                ]); ?>
            </div>
    </div>
</div>
