<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\NavWidget;
use yii\helpers\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="flex flex-col h-screen bg-gray-50">
<?php $this->beginBody() ?>
        <header id="header">
            <?=
            NavWidget::widget([
                'items' => [
                    Yii::t('app', 'Home') => ['site/index'],
                    Yii::t('app', 'Groups') => ['groups/index'],
                    Yii::t('app', 'Create') => ['post/create'],
                    Yii::t('app', 'Search') => ['search/index'],
                ],
                'brandLabel' => 'Fitstagram',
            ]);?>
</header>

<main id="main" class="flex-grow" role="main">
    <div class="container mx-auto px-4 py-8">
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-center text-sm text-gray-600">
            footer
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
