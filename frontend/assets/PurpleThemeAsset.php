<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class PurpleThemeAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/sources/';

    public $css = ASSET_BUNDLE ? [
        'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700,700i|Roboto:300,300i,400,700,700i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'style.css?version=5',
    ] : [
        'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,700,700i|Roboto:300,300i,400,700,700i&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'style.css?version=5',
    ];

    public $js = ASSET_BUNDLE ? [
        'script.js?version=5',
    ] : [
        'script.js?version=5',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}