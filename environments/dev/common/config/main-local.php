<?php
return [
    'bootstrap' => ['debug'],
    'modules'   => [
        'debug' => [
            'class'           => 'yii\debug\Module',
            'enableDebugLogs' => false,
            'allowedIPs'      => [''],
            'panels' => [
                'mongodb' => [
                    'class' => 'yii\mongodb\debug\MongoDbPanel',
                ],
            ],
        ],
    ]
];