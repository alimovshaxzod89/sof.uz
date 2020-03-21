<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/app';
    public $css        = ASSET_BUNDLE ? [
        'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700,700i|Roboto:300,300i,400,700,700i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'css/app.min.css',
    ] : [
        'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700,700i|Roboto:300,300i,400,700,700i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'css/block-library.css',
        'css/main.css',
        'css/style.css',
    ];

    public $js = ASSET_BUNDLE ? [
        'js/app.min.js',
    ] : [
        'js/imagesloaded.min.js',
        'js/masonry.min.js',
        'js/theia-sticky-sidebar.min.js',
        'js/magsy.min.js',
        'js/scripts.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
