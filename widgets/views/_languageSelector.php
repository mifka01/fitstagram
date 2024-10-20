<?php

/**
 * @var yii\web\View $this
 * @var array<string, string> $languages
 * @var string $currentLanguage
 */

use yii\helpers\Html;
use yii\helpers\Url;

$changeLanguageUrl = Url::to(['/language/change']);

$js = <<<JS
$('#language-selector').on('change', function() {
    var url = $(this).data('url') + '?language=' + $(this).val();
    window.location.href = url; 
});

JS;

$this->registerJs($js);

?>

<div>
    <?= Html::dropDownList(
        'language',
        $currentLanguage,
        $languages,
        [
        'id' => 'language-selector',
            'class' => 'block pl-3 pr-8 py-1.5 text-sm border border-gray-200 rounded-md text-gray-500 focus:outline-none focus:ring-1 focus:ring-orange-500 focus:border-orange-500',
            'data-url' => $changeLanguageUrl,
        ]
    ) ?>
</div>

<?php


