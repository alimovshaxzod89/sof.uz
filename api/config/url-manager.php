<?php

return [
    'class'                     => 'codemix\localeurls\UrlManager',
    'languages'                 => ['oz' => 'oz-UZ', 'uz' => 'uz-UZ'],
    'enableLanguageDetection'   => false,
    'enableLanguagePersistence' => true,
    'showScriptName'            => false,
    'enablePrettyUrl'           => true,
    'rules'                     => [
        'v1/<controller:\w+>/<id:[a-z0-9]{24,24}>'              => 'v1/<controller>/view',
        'v1/<controller:\w+>/<action:\w+>/<id:[a-z0-9]{24,24}>' => 'v1/<controller>/<action>',
        'v1/<controller:\w+>/<action:\w+>'                      => 'v1/<controller>/<action>',
        'v1/<controller:\w+>/<action:list>/<page:\d+>/?'        => 'v1/<controller>/<action>',
        '<controller:\w+>/<action:\w+>'                         => '<controller>/<action>',
    ],
];