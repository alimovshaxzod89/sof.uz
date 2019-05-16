<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace common\models\old;

use yii\db\ActiveRecord;

/**
 * Class Currency
 * @package common\models
 * @property string id
 * @property string title
 * @property string type
 * @property string lan
 * @property string limit_click
 * @property string limit_view
 */
class OldCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'category';
    }

}