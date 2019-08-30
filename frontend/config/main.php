<?php

use common\components\BootstrapEvents;
use common\components\Config;
use ymaker\social\share\configurators\Configurator;
use ymaker\social\share\drivers\Facebook;
use ymaker\social\share\drivers\GooglePlus;
use ymaker\social\share\drivers\Odnoklassniki;
use ymaker\social\share\drivers\Telegram;
use ymaker\social\share\drivers\Twitter;
use ymaker\social\share\drivers\Vkontakte;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);


return [
    'id'                  => 'app-frontend',
    'name'                => 'Sof.uz',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => [
        'config',
        BootstrapEvents::class,
    ],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute'        => '/',
    'language'            => Config::LANGUAGE_DEFAULT,
    'modules'             => [

    ],
    'components'          => [
        'user'                 => [
            'identityClass'       => 'common\models\User',
            'enableAutoLogin'     => true,
            'absoluteAuthTimeout' => 84600,
            'loginUrl'            => ['account/login'],
        ],
        'view'                 => [
            'class' => 'frontend\components\View',
        ],
        'log'                  => [
            'traceLevel' => YII_DEBUG ? 8 : 3,
            'targets'    => [
                [
                    'class'       => 'yii\log\FileTarget',
                    'levels'      => ['error'],
                    'enabled'     => true,
                    'logVars'     => ['_GET'],
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
            ],
        ],
        'errorHandler'         => [
            'errorAction' => 'site/error',
        ],
        'request'              => [
            'enableCookieValidation' => true,
            'cookieValidationKey'    => 'Ci@1ouPzsfsef2UtTfKwTLKXn9s6myjpnWO',
        ],
        'assetManager'         => [
            'bundles'         => [
                'yii\bootstrap\BootstrapAsset'       => false,
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\web\JqueryAsset'                => [
                    'sourcePath' => '@frontend/assets/app',
                    'jsOptions'  => [
                        'position' => \frontend\components\View::POS_HEAD
                    ],
                    'js'         => [
                        'js/jquery.min.js',
                        'js/jquery-migrate.min.js',
                    ],
                ],
            ],
            'linkAssets'      => true,
            'appendTimestamp' => true,
            'basePath'        => '@static/assets',
            'baseUrl'         => getenv('STATIC_URL') . 'assets/',
        ],
        'socialShare'          => [
            'class'              => Configurator::class,
            'enableDefaultIcons' => true,
            'options'            => [
                'class' => 'service',
                'role'  => 'sharer',
            ],
            'icons'              => [
                Vkontakte::class     => 'vk',
                Facebook::class      => 'facebook',
                Twitter::class       => 'twitter',
                GooglePlus::class    => 'google',
                Telegram::class      => 'paper-plane',
                Odnoklassniki::class => 'odnoklassniki',
            ],
            'socialNetworks'     => [

                'facebook' => [
                    'label' => '',
                    'class' => Facebook::class,
                ],
                'twitter'  => [
                    'label' => '',
                    'class' => Twitter::class,
                ],
                'telegram' => [
                    'label' => '',
                    'class' => Telegram::class,
                ],

            ],
        ],
        'authClientCollection' => [
            'class'   => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class'        => 'yii\authclient\clients\Facebook',
                    'clientId'     => getenv('FACEBOOK_CLIENT_ID'),
                    'clientSecret' => getenv('FACEBOOK_CLIENT_SECRET'),
                    //'authUrl'      => 'https://www.facebook.com/dialog/oauth?display=popup',

                    'attributeNames' => [
                        'name',
                        'email',
                        'picture',
                    ],
                ],
                'twitter'  => [
                    'class'           => 'yii\authclient\clients\Twitter',
                    'attributeParams' => [
                        'include_email' => 'true',
                    ],
                    'viewOptions'     => [
                        'popupWidth'  => 800,
                        'popupHeight' => 500,
                    ],
                    'consumerKey'     => getenv('TWITTER_CONSUMER_KEY'),
                    'consumerSecret'  => getenv('TWITTER_CONSUMER_SECRET'),
                ],
            ],
        ],
        'urlManager'           => require(__DIR__ . '/url-manager.php'),
    ],
    'params'              => $params,
];
