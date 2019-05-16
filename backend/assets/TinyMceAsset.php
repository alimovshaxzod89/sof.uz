<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\assets;


use yii\web\AssetBundle;

class TinyMceAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $sourcePath = '@app/assets/tinymce';

    public $js  = [
        'cc/plugin.js',
        'cw/plugin.js',
        'myembed/plugin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

}