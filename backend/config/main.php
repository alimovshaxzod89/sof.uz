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
    'name'                => 'Sof.uz',
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
        'urlManager'   => require(__DIR__ . '/url-manager.php'),
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
            'pageId'      => getenv('FB_PAGE_ID'),
            'accessToken' => getenv('FB_ACCESS_TOKEN'),
        ],
        'twitter'      => [
            'class'             => TwitterShare::class,
            'consumerKey'       => getenv('TWITTER_CONSUMER_KEY'),
            'consumerSecret'    => getenv('TWITTER_CONSUMER_SECRET'),
            'accessToken'       => getenv('TWITTER_TOKEN'),
            'accessTokenSecret' => getenv('TWITTER_TOKEN_SECRET'),
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
