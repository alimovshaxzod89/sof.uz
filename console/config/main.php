<?php


use common\components\BootstrapEvents;
use yii\queue\file\Queue;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-console',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => [
        'log',
        'config',
        'queue',
        BootstrapEvents::class,
    ],
    'controllerNamespace' => 'console\controllers',
    'controllerMap'       => [
        'migrate' => 'console\components\FixedMigrateController',
        'queue'   => 'yii\queue\file\Command',
        'fixture' => [
            'class'           => 'yii\faker\FixtureController',
            'namespace'       => 'common\fixtures',
            'templatePath'    => '@common/tests/unit/templates/fixtures',
            'fixtureDataPath' => '@common/fixtures/data',
            'globalFixtures'  => [],
        ],
    ],
    'components'          => [
        'queue'      => [
            'class' => Queue::className(),
        ],
        'urlManager' => [
            'class'                     => 'codemix\localeurls\UrlManager',
            'languages'                 => ['uz' => 'uz-UZ', 'oz' => 'oz-UZ'],
            'enableLanguageDetection'   => false,
            'enableLanguagePersistence' => false,
            'showScriptName'            => false,
            'enablePrettyUrl'           => true,
            'baseUrl'                   => Yii::getAlias('@frontendUrl'),
            'rules'                     => [
                '/'                                                => 'site/index',
                '/weather'                                         => 'site/weather',
                '/update-browser'                                  => 'site/browser',
                '/search'                                          => 'category/search',
                '/aloqa'                                           => 'site/contact',
                '/video'                                           => 'category/video',
                '/audio'                                           => 'category/audio',
                '/photo'                                           => 'category/photo',
                'audio/<slug:[a-z0-9-]+>'                          => 'category/audio',
                'feed/<type:[a-z0-9-]+>'                           => 'category/feed',
                'yangiliklar/<type:[a-z0-9-]+>'                    => 'category/feed',
                '/feed'                                            => 'category/feed',
                '/yangiliklar'                                     => 'category/feed',
                'tag/<slug:[a-z0-9-]+>'                            => 'category/tag',
                '/authors'                                         => 'author/index',
                '/mualliflar'                                      => 'author/index',
                '/mualliflar/<login:[a-z0-9-]+>'                   => 'author/view',
                '/mualliflar/<login:[a-z0-9-]+>/<slug:[a-z0-9-]+>' => 'author/post',
                'preview/<id:[a-z0-9]{24,24}>'                     => 'post/preview',
                'p/<id:[a-z0-9-]+>'                                => 'post/short',
                '/<id:[a-z0-9-]{3,4}>'                             => 'post/short',
                '<slug:[a-z0-9-]+>'                                => 'category/view',
                'post/<slug:[a-z0-9-]+>'                           => 'post/view',
                'page/<slug:[a-z0-9-]+>'                           => 'page/view',
                'author/view/<login:\w+>'                          => 'author/view',
                'polls/<type:[a-z0-9-]+>'                          => 'poll/index',
                'poll/<id:[a-z0-9-]+>'                             => 'poll/view',
                '<category:[a-z0-9-]+>/<slug:[a-z0-9-]+>'          => 'post/view',
                '<controller:\w+>/<action:\w+>'                    => '<controller>/<action>',
                '<controller:\w+>/<id:\d+>'                        => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'           => '<controller>/<action>',
            ],
        ],
        'log'        => [
            'targets' => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params'              => $params,
];
