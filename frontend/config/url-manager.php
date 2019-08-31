<?php

return [
    'class'                        => 'codemix\localeurls\UrlManager',
    'languages'                    => ['oz' => 'oz-UZ', 'uz' => 'uz-UZ'],
    'enableLanguageDetection'      => false,
    'enableLanguagePersistence'    => true,
    'showScriptName'               => false,
    'enablePrettyUrl'              => true,
    'rules'                        => [
        '/'       => 'site/index',
        '/search' => 'category/search',

        '/<short:[a-z0-9-]{3,4}>' => 'post/short',
        '<slug:[a-z0-9-]+>'       => 'category/view',

        'author/<slug:[a-z0-9-]+>' => 'category/author',
        'tag/<slug:[a-z0-9-]+>'    => 'category/tag',

        'post/<slug:[a-z0-9-]+>'                 => 'post/view',
        'page/<slug:[a-z0-9-]+>'                 => 'page/view',
        //'<category:[a-z0-9-]+>/<slug:[a-z0-9-]+>' => 'post/view',
        '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
        '<controller:\w+>/<id:\d+>'              => '<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
    ],
];