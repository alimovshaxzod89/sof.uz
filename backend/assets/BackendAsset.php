<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class BackendAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $sourcePath = '@app/assets/backend';
    public $js         = [
        'js/ui/accordion.js',
        'js/ui/animate.js',
        'js/ui/toggle.js',
        'js/urban-constants.js',
        'js/jquery.nestable.js',
        'js/jquery.formatter.min.js',
        'js/js.cookie.js',
        'js/Chart.min.js',
        'js/scripts.js',
    ];

    public $css        = [
        'css/panel.css',
        'css/feather.css',
        'css/animate.css',
        'css/urban.css',
        'css/urban.skins.css',
        'css/styles.css',
    ];
    public $depends    = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'backend\widgets\checkbo\CheckBoAsset',
    ];
}
