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
        'urlManager' => require(dirname(dirname(__DIR__)) . '/frontend/config/url-manager.php'),
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
