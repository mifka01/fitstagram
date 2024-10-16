<?php

namespace app\services;

use yii\web\UnauthorizedHttpException;

class MigrationService
{
    public function validateToken(string $migrateToken): void
    {
        $token = env("MIGRATE_TOKEN");
        if (empty($migrateToken) || $migrateToken !== $token) {
            throw new UnauthorizedHttpException('Invalid Token');
        }
    }

    public function runMigrations(): string
    {
        ob_start();
        
        $oldApp = \Yii::$app;

        $config = require \Yii::getAlias('@app') . '/config/console.php';
        new \yii\console\Application($config);

        \Yii::$app->runAction('migrate', ['interactive' => false]);

        \Yii::$app = $oldApp;

        if (($output = ob_get_clean()) === false) {
            return 'No new migrations found';
        }

        return nl2br($output);
    }
}
