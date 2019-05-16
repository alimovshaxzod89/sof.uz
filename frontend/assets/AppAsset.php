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
        'https://fonts.googleapis.com/css?family=Roboto:400,500,700|PT+Serif:400,400i,600|IBM+Plex+Serif:500',
        'css/app.min.css',
    ] : [
        'https://fonts.googleapis.com/css?family=Roboto:400,500,700|PT+Serif:400,400i,600|IBM+Plex+Serif:500',
        'css/animation.css',
        'css/fontello.css',
        'css/fontello-ie7.css',
        'css/lightbox.css',
        'css/style.css',
        'css/custom.css',
    ];
    public $js         = ASSET_BUNDLE ? [
        'js/app.min.js',
    ] : [
        'js/easing.min.js',
        'js/owl-carousel.min.js',
        'js/theia-sticky-sidebar.min.js',
        'js/magnific-popup.js',
        'js/object-fit-images.js',
        'js/jquery.slick.js',
        'js/headroom.min.js',
        'js/scripts.js',
        'js/custom.js',
    ];
    public $depends    = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
