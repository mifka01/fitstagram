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
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
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
