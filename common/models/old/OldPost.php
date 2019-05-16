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
 * @property string description
 * @property string author
 * @property string poster_w
 * @property string text
 * @property string date
 * @property string lan
 * @property string dolzorab
 * @property string category_id
 * @property string link
 * @property string viewed
 * @property string keyword
 * @property string descrept
 */
class OldPost extends ActiveRecord
{
    public static function tableName()
    {
        return 'news';
    }

}