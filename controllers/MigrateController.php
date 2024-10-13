<?php

namespace app\controllers;

use yii\web\UnauthorizedHttpException;

class MigrateController extends \yii\web\Controller
{
    public function actionUp($migrateToken = '')
    {
        $token = env("MIGRATE_TOKEN");

        if (empty($token) || $token !== $migrateToken) {
            throw new UnauthorizedHttpException('Invalid Token');
        }

        ob_start();
        // Keep current application
        $oldApp = \Yii::$app;
        // Load Console Application config
        $config = require \Yii::getAlias('@app') . '/config/console.php';
        new \yii\console\Application($config);
        \Yii::$app->runAction('migrate', ['interactive' => false]);
        // Revert application
        \Yii::$app = $oldApp;

        return nl2br(ob_get_clean());
    }
}
