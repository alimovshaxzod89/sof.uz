<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\widgets\filekit\widget;

use yii\web\AssetBundle;

class BlueimpTmplAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-tmpl';

    public $js = [
        'js/tmpl.min.js'
    ];
}
