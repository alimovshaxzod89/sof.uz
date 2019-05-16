<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class CommentsAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/comments';

    public $js = [
        'js/jquery-comments.js',
    ];

    public $css     = [
        'css/jquery-comments.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}