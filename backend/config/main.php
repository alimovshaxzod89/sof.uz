<?php

use backend\components\sharer\FacebookShare;
use backend\components\sharer\TelegramBot;
use backend\components\sharer\TwitterShare;
use common\components\Config;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-backend',
    'name'                => 'minbar.uz',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute'        => 'post/index',
    'language'            => Config::LANGUAGE_CYRILLIC,
    'bootstrap'           => ['log', 'config'],
    'modules'             => [

    ],
    'components'          => [
        'request'      => [
            'enableCsrfValidation' => false,
        ],
        'urlManager'   => [
            'class'                     => 'codemix\localeurls\UrlManager',
            'languages'                 => ['oz' => 'oz-UZ', 'uz' => 'uz-UZ'],
            'enableLanguageDetection'   => false,
            'enableLanguagePersistence' => false,
            'showScriptName'            => false,
            'enablePrettyUrl'           => true,
            'rules'                     => [
                '<controller:\w+>/<id:[a-z0-9]{24,24}>'                                   => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:[a-z0-9]{24,24}>'                      => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'                                           => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<type:[a-z]{3,16}>'                        => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>/<id:[a-z0-9]{24,24}>/<social:[a-z]{3,16}>' => '<controller>/<action>',
            ],
        ],
        'user'         => [
            'identityClass'   => 'common\models\Admin',
            'enableAutoLogin' => true,
            'loginUrl'        => '/dashboard/login',
            'identityCookie'  => [
                'name' => '_backendUser',
            ],
        ],
        'view'         => [
            'class' => 'backend\components\View',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 3,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'dashboard/error',
        ],
        'telegram'     => [
            'class'       => TelegramBot::class,
            'accessToken' => getenv('BOT_TOKEN'),
            'channelId'   => getenv('CHANNEL_ID'),
        ],
        'facebook'     => [
            'class'       => FacebookShare::class,
            'pageId'      => '2277876505771982',
            'accessToken' => '1297980230257035|F2pBLoPcGk_2C7N9aHOBB0lyGHM',
        ],
        'twitter'      => [
            'class'             => TwitterShare::class,
            'consumerKey'       => getenv('TWITTER_CONSUMER_KEY'),
            'consumerSecret'    => getenv('TWITTER_CONSUMER_SECRET'),
            'accessToken'       => '752737745462816768-EVB0m93gCTejbw76bn8P3GYa6rqOfuS',
            'accessTokenSecret' => 'dUzJUnRFrELGKQQjWui1maEKvtt6ypZgc01VmiA1obyAP',
        ],
        'assetManager' => array(
            'linkAssets'      => true,
            'appendTimestamp' => true,
            'bundles'         => [
            ],
        ),
    ],
    'params'              => $params,
];
