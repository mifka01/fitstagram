<?php

namespace app\controllers;

use app\services\MigrationService;

class MigrationController extends \yii\web\Controller
{
    public function actionUp(string $migrateToken = ''): string
    {
        $migrationService = new MigrationService();
        $migrationService->validateToken($migrateToken);

        return $migrationService->runMigrations();
    }
}
