<?php

namespace backend\assets;


use yii\web\AssetBundle;

class UrbanAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $sourcePath = '@vendor/bower-asset';

    public $js  = [
        /*'jquery.easing/js/jquery.easing.min.js',
        'jquery-countTo/jquery.countTo.js',*/
        'perfect-scrollbar/js/perfect-scrollbar.jquery.js',
        'd3/d3.min.js',
        'theia-sticky-sidebar/dist/theia-sticky-sidebar.min.js',
    ];
    public $css = [
        'perfect-scrollbar/css/perfect-scrollbar.min.css',
        'chosen_v1.4.0/chosen.min.css',
        'components-font-awesome/css/font-awesome.min.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}