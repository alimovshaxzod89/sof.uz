<?php

namespace common\models\old;

use yii\db\ActiveRecord;

/**
 * @property string id
 * @property string text
 * @property string slug
 *
 * @package common\models
 */
class OldTag extends ActiveRecord
{
    public static function tableName()
    {
        return 'tags';
    }

}