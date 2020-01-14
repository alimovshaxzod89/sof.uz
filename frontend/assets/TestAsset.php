<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class TestAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/app';

    public $js = [
        'js/abt.embed.js',
    ];
}