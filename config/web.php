<?php

use yii\helpers\ArrayHelper;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'name' => 'FITstagram',
    'bootstrap' => ['log'],
    'timeZone' => 'Europe/Prague',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'main-search' => [
            'class' => '\kazda01\search\SearchModule',
            'rules' => [
                [
                    'allow' => true,
                ],
            ],
            'searchConfig' => [
                'UserSearch' => [
                    'columns' => ['username'],
                    'matchTitle' => function () {
                        return Yii::t('app/model', 'User');
                    },
                    'matchText' => function ($model) {
                        return $model->username;
                    },
                    'only_if' => function ($model) {
                        return !$model->deleted && !$model->banned;
                    },
                    'route' => '/user/profile',
                    'route_params' => function ($model) {
                        return ['username' => $model->username];
                    },
                    'group_by' => true,
                ],
                'GroupSearch' => [
                    'columns' => ['name'],
                    'matchTitle' => function () {
                        return Yii::t('app/group', 'Group');
                    },
                    'matchText' => function ($model) {
                        return $model->name;
                    },
                    'only_if' => function ($model) {
                        return !$model->deleted && !$model->banned;
                    },
                    'route' => '/group/index',
                    'route_params' => function ($model) {
                        return ['id' => $model->id];
                    },
                    'group_by' => true,
                ],
                'TagSearch' => [
                    'columns' => ['name'],
                    'matchTitle' => function () {
                        return Yii::t('app/tag', 'Tag');
                    },
                    'matchText' => function ($model) {
                        return $model->name;
                    },
                    'route' => '/tag/view',
                    'route_params' => function ($model) {
                        return ['id' => $model->id];
                    },
                    'group_by' => true,
                ],
            ],
        ],
    ],
    'on beforeRequest' => function () {
        Yii::$app->language = Yii::$app->params['defaultLanguage'];
        $cookies = Yii::$app->request->cookies;
        $language = $cookies->getValue('language', Yii::$app->sourceLanguage);
        if (ArrayHelper::isIn($language, array_keys(Yii::$app->params['languages']))) {
            Yii::$app->language = $language;
        }
    },
    'components' => [
        'reCaptcha' => [
            'class' => 'himiklab\yii2\recaptcha\ReCaptchaConfig',
            'siteKeyV3' => env('GOOGLE_RECAPTCHA_V3_SITE_KEY'),
            'secretV3' => env('GOOGLE_RECAPTCHA_V3_SECRET_KEY'),
        ],
        'lastActiveService' => [
            'class' => 'app\services\LastActiveService',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'writeCallback' => function ($session) {
                if (Yii::$app->user->getIsGuest()) {
                    return [];
                }
                return [
                    'user_id' => Yii::$app->user->id,
                    'last_write' => time()
                ];
            }
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'votnM2Cebm9UzncB43mBDgsgaS1-G-39',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\symfonymailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'dsn' => 'smtp://' . env('MAIL_USERNAME') . ':' . env('MAIL_PASS') . '@' . env('MAIL_HOST') . ':' . env('MAIL_PORT'),
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'post/index',
                'user/profile/<username:[\.\w-]+>' => 'user/profile',
                '<path:post-images/.+>' => 'post/get-media-file',

            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'formatter' => [
            'class' => '\app\components\Formatter',
            'defaultTimeZone' => 'Europe/Prague',
        ],
        'i18n' => [
            'translations' => [
                'app/*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/user' => 'user.php',
                        'app/model' => 'model.php',
                        'app/auth' => 'auth.php',
                        'app/error' => 'error.php',
                        'app/mail' => 'mail.php',
                        'app/group' => 'group.php',
                        'app/post' => 'post.php',
                        'app/tag' => 'tag.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            \yii\widgets\ActiveForm::class => [
                'errorCssClass' => 'error-input',
                'validationStateOn' => \yii\widgets\ActiveForm::VALIDATION_STATE_ON_INPUT,
            ],
            \yii\widgets\ActiveField::class => [
                'errorOptions' => ['class' => 'error-message']
            ],
            \himiklab\yii2\recaptcha\ReCaptchaValidator3::class => \app\components\ReCaptcha3Validator::class,
            \app\components\ReCaptcha3Validator::class => [
                'message' => 'Ověření reCAPTCHA selhalo. Prosím zkuste to znovu. Pokud se problém opakuje, kontaktujte nás.',
                'threshold' => 1.2,
            ],
            \himiklab\yii2\recaptcha\ReCaptcha3::class => \app\components\ReCaptcha3::class,
            \yii\widgets\LinkPager::class => [
                'options' => ['class' => 'flex justify-center gap-2 my-4'],
                'linkOptions' => ['class' => 'page-link px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50'],
                'activePageCssClass' => 'border-orange-500 bg-orange-50 text-orange-600',
                'disabledPageCssClass' => 'border-gray-200 text-gray-400 cursor-not-allowed',
                'maxButtonCount' => 5,
            ],
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
