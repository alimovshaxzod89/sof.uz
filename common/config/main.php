<?php

use backend\components\sharer\FacebookShare;
use backend\components\sharer\TelegramBot;
use backend\components\sharer\TwitterShare;
use yii\helpers\ArrayHelper;

$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$root   = dirname(dirname(__DIR__));

$config = [
    'id'         => 'advanced',
    'basePath'   => dirname(__DIR__),
    'timeZone'   => 'Asia/Tashkent',
    'vendorPath' => $root . '/vendor',
    'bootstrap'  => ['log', 'config'],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset'
    ],
    'components' => [

        'apiUrl' => ArrayHelper::merge(
            ['baseUrl' => Yii::getAlias('@apiUrl')],
            require($root . '/api/config/url-manager.php')
        ),

        'viewUrl' => ArrayHelper::merge(
            ['baseUrl' => Yii::getAlias('@frontendUrl')],
            require($root . '/frontend/config/url-manager.php')
        ),

        'editUrl' => ArrayHelper::merge(
            ['baseUrl' => Yii::getAlias('@backendUrl')],
            require($root . '/api/config/url-manager.php')
        ),

        'i18n'   => [
            'translations' => [
                'app*' => [
                    'class'                 => 'common\components\MongoMessageSource',
                    'forceTranslation'      => true,
                    'enableCaching'         => !YII_DEBUG,
                    'cachingDuration'       => 3600,
                    'sourceLanguage'        => 'uz-UZ',
                    'on missingTranslation' => [
                        'common\components\MongoMessageSource',
                        'handleMissingTranslation',
                    ],
                ],
                'yii'  => [
                    'class'                 => 'common\components\MongoMessageSource',
                    'forceTranslation'      => true,
                    'enableCaching'         => true,
                    'cachingDuration'       => 3600,
                    'sourceLanguage'        => 'en-US',
                    'on missingTranslation' => [
                        'common\components\MongoMessageSource',
                        'handleMissingTranslation',
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class'            => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@common/mail',
            'transport'        => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => 'smtp.yandex.ru',
                'username'   => getenv('EMAIL_LOGIN'),
                'password'   => getenv('EMAIL_PASSWORD'),
                'port'       => '587',
                'encryption' => 'tls',
            ],
            'useFileTransport' => false,
        ],
        'db'     => require(__DIR__ . '/db.php'),

        'assetManager' => array(
            'linkAssets'      => true,
            'appendTimestamp' => true,
        ),
        'config'       => [
            'class' => 'common\components\Config',
        ],
        'formatter'    => [
            'class'             => 'common\components\Formatter',
            'currencyCode'      => 'UZS',
            'dateFormat'        => 'dd/MM/yyyy',
            'datetimeFormat'    => 'dd/MM/yyyy HH:mm:ss',
            'decimalSeparator'  => '.',
            'thousandSeparator' => ' ',
            'timeZone'          => 'Asia/Tashkent',
            'defaultTimeZone'   => 'Asia/Tashkent',
        ],
        'mongodb'      => [
            'class' => '\yii\mongodb\Connection',
            'dsn'   => getenv('MONGODB_DSN'),
        ],
        'fileStorage'  => [
            'class'      => '\trntv\filekit\Storage',
            'baseUrl'    => '@staticUrl/uploads',
            'filesystem' => [
                'class' => 'common\components\LocalFileSystemBuilder',
                'path'  => '@static/uploads',
            ],
        ],
        'reCaptcha'    => [
            'name'    => 'reCaptcha',
            'class'   => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => getenv('RE_CAPTCHA_KEY'),
            'secret'  => getenv('RE_CAPTCHA_SECRET'),
        ],
        'redis'        => [
            'class'    => 'yii\redis\Connection',
            'hostname' => getenv('REDIS_HOSTNAME'),
            'port'     => getenv('REDIS_PORT'),
            'database' => getenv('REDIS_DB'),
        ],
        'session'      => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => getenv('REDIS_HOSTNAME'),
                'port'     => getenv('REDIS_PORT'),
                'database' => getenv('REDIS_DB'),
            ],
        ],
        'cache'        => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => getenv('REDIS_HOSTNAME'),
                'port'     => getenv('REDIS_PORT'),
                'database' => getenv('REDIS_DB'),
            ],
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
    ],
    'params'     => $params,
];
return $config;
