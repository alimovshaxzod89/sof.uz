<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class TextcompleteAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/jquery-textcomplete/dist/';

    public $js = [
        'jquery.textcomplete.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}