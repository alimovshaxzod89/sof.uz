<?php

namespace console\models;

use common\models\Category as NewCategory;

/**
 * Class Post
 * @package console\models
 * @property NewCategory new
 */
class Category extends \common\models\old\OldCategory
{
    public function getNew()
    {
        return $this->hasOne(NewCategory::class, ['old_id' => 'id']);
    }
}
