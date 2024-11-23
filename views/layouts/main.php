<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\User;
use app\widgets\Alert;
use app\widgets\NavWidget;
use yii\helpers\Html;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css', 'integrity' => 'sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==', 'crossorigin' => 'anonymous', 'referrerpolicy' => 'no-referrer']);

$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => Yii::getAlias('@web/favicon/favicon-96x96.png'), 'sizes'=>'96x96']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/svg+xml', 'href' => Yii::getAlias('@web/favicon/favicon.svg')]);
$this->registerLinkTag(['rel' => 'shortcut icon', 'href' => Yii::getAlias('@web/favicon/favicon.ico')]);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'sizes' => '180x180', 'href' => Yii::getAlias('@web/favicon/apple-touch-icon.png')]);
$this->registerMetaTag(['name' => 'apple-mobile-web-app-title', 'content' => 'FITstagram']);
$this->registerLinkTag(['rel' => 'manifest', 'href' => Yii::getAlias('@web/favicon/site.webmanifest')]);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode(($this->title ? $this->title . ' | ' : '') . Yii::$app->name) ?></title>
    <?php $this->head() ?>
</head>
<body class="flex flex-col h-screen bg-gray-50">
<?php $this->beginBody() ?>
        <header id="header">
            <?=
            NavWidget::widget([
                'items' => [
                    [
                        'label'=> Yii::t('app', 'Home'),
                        'route' => ['post/index'],
                        'icon'=> 'fas fa-home'
                    ],
                    [
                        'label'=> Yii::t('app', 'Groups'),
                        'route' => ['group/index'],
                        'icon'=> 'fas fa-users'
                    ],
                    [
                        'label'=> Yii::t('app', 'Create'),
                        'route' => ['post/create'],
                        'icon'=> 'fas fa-plus'
                    ],
                    [
                        'label'=> Yii::t('app', 'Search'),
                        'route' => ['site/search'],
                        'icon' => 'fas fa-search'
                    ],
                ],
                'actionButton' => [
                    [
                        'label' => Yii::t('app', 'Profile'),
                        'route' => ['user/profile', 'username' => $user = ($user = User::findOne(Yii::$app->user->id)) ? $user->username : ''],
                        'icon' => 'fas fa-user',
                        'visible'=> !Yii::$app->user->isGuest
                    ],
                    [
                        'label' => Yii::t('app', 'Login'),
                        'route' => ['auth/login'],
                        'icon' => 'fas fa-sign-in-alt',
                        'visible' => Yii::$app->user->isGuest
                    ]
                ],
                'brandLabel' => 'FITstagram',
                'brandUrl' => ['post/index'],
                'brandImage' => Yii::getAlias('@web/images/logo.png'),
            ]);?>
            <?= Alert::widget() ?>
</header>

<main id="main" class="flex-grow" role="main">
<div class="container mx-auto mb-8">
        <?= $content ?>
    </div>
</main>

<?= $this->render('_footer') ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
