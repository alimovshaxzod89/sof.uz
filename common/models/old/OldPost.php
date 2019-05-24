<?php

namespace common\models\old;

use yii\db\ActiveRecord;

/**
 * @property string id
 * @property string title
 * @property string full
 * @property string views
 * @property string category_id
 * @property string date
 * @property string img
 * @property string user_id
 * @property string comment
 * @property string lang
 * @property string status
 * @property string top
 * @property string from_user
 * @property string slug
 * @property string short
 * @property string photo
 * @property string mobile
 *
 * @package common\models
 */
class OldPost extends ActiveRecord
{
    public static function tableName()
    {
        return 'news';
    }

}