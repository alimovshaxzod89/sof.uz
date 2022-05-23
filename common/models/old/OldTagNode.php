<?php

namespace common\models\old;

use yii\db\ActiveRecord;

class OldTagNode extends ActiveRecord
{
    public static function tableName()
    {
        return 'tags_nodes';
    }
}