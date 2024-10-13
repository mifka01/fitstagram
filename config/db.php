<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . env("DB_HOST") . ';dbname=' . env("DB_NAME"),
    'username' => env("DB_LOGIN"),
    'password' => env("DB_PASS"),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',
];
