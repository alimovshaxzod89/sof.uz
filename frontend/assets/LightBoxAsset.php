<?php
/**
 * Created by PhpStorm.
 * User: abdujabbor
 * Date: 7/12/16
 * Time: 2:20 PM
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class LightBoxAsset extends AssetBundle
{
    public $sourcePath = '@bower/simplelightbox/dist/';

    public $js = [
        'simple-lightbox.min.js',
    ];

    public $css     = [
        'simplelightbox.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}