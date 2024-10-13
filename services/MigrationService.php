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
        
        // Keep current application
        $oldApp = \Yii::$app;

        // Load Console Application config
        $config = require \Yii::getAlias('@app') . '/config/console.php';
        new \yii\console\Application($config);

        // Run migration command
        \Yii::$app->runAction('migrate', ['interactive' => false]);

        // Revert application
        \Yii::$app = $oldApp;

        return nl2br(ob_get_clean());
    }
}
