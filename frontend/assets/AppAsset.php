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
        'https://fonts.googleapis.com/css?family=Lato:400,400i,700,900|Merriweather:400,700&subset=latin,latin-ext&amp;ver=1.3.0',
        'css/app.min.css',
    ] : [
        'https://fonts.googleapis.com/css?family=Lato:400,400i,700,900|Merriweather:400,700&subset=latin,latin-ext&amp;ver=1.3.0',
        'css/block-library.css',
        'css/main.css',
        'css/style.css',
    ];
    public $js         = ASSET_BUNDLE ? [
        'js/app.min.js',
    ] : [
        'js/wpcf7.js',
        'js/imagesloaded.min.js',
        'js/masonry.min.js',
        'js/magsy.min.js',
    ];
    public $depends    = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
