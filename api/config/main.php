<?php

use common\components\Config;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-backend',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap'           => ['log', 'config'],
    'language'            => Config::LANGUAGE_DEFAULT,
    'modules'             => [

    ],
    'components'          => [
        'urlManager' => require(__DIR__ . '/url-manager.php'),
        'response'   => [
            'class'         => 'yii\web\Response',
            'format'        => yii\web\Response::FORMAT_JSON,
            'on beforeSend' => [
                'api\components\RequestLogger',
                'logRequest',
            ],
        ],
        'request'    => [
            'enableCookieValidation' => false,
            'parsers'                => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user'       => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'log'        => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'       => 'yii\log\FileTarget',
                    'levels'      => ['error', 'warning', 'trace'],
                    'enabled'     => YII_DEBUG,
                    'logVars'     => [],
                    'categories'  => ['request'],
                    'logFile'     => '@app/runtime/logs/request.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ], [
                    'class'       => 'yii\log\FileTarget',
                    'levels'      => ['error'],
                    'enabled'     => true,
                    'logVars'     => ['_GET', '_POST', '_FILES'],
                    'logFile'     => '@app/runtime/logs/error.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
    ],

    'params' => $params,
];
