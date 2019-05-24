<?php

namespace common\models\old;

use yii\db\ActiveRecord;

/**
 * @property string id
 * @property string name
 * @property string url
 * @property string uz
 * @property string ru
 *
 * @package common\models
 */
class OldCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'category';
    }

}