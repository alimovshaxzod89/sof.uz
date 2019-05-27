<?php

return [
    'class'               => 'yii\db\Connection',
    'dsn'                 => getenv('DB_DSN'),
    'username'            => getenv('DB_USERNAME'),
    'password'            => getenv('DB_PASSWORD'),
    'tablePrefix'         => getenv('DB_TABLE_PREFIX'),
    'charset'             => 'utf8',
    'enableSchemaCache'   => true,
    'enableQueryCache'    => false,
    'schemaCacheDuration' => 3600,
    'queryCacheDuration'  => 3600,
];
