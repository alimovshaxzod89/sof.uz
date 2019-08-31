<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/app';
    public $css        = /*ASSET_BUNDLE ? [
        //'https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Merriweather:300,300i,400,700,700i&display=swap&subset=cyrillic',
        'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700,700i|Roboto:300,300i,400,700,700i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'css/app.min.css',
    ] :*/ [
        //'https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Merriweather:300,300i,400,700,700i&display=swap&subset=cyrillic',
        'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700,700i|Roboto:300,300i,400,700,700i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'css/block-library.css',
        'css/main.css',
        'css/style.css',
    ];

    public $js = /*ASSET_BUNDLE ? [
        'js/app.min.js',
    ] : */[
        //'js/wpcf7.js',
        'js/imagesloaded.min.js',
        'js/masonry.min.js',
        'js/theia-sticky-sidebar.min.js',
        'js/magsy.min.js',
    ];

    public function init()
    {
        if (!YII_DEBUG) {
            //array_unshift($this->css, 'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700,700i|Roboto:300,300i,400,700,700i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext');
            //array_unshift($this->css, 'https://fonts.googleapis.com/css?family=Roboto:400,500,700|PT+Serif:400,400i,600|IBM+Plex+Serif:500');
            //array_unshift($this->css, 'https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Merriweather:300,300i,400,700,700i&display=swap&subset=cyrillic');
        }

        parent::init(); // TODO: Change the autogenerated stub
    }

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
