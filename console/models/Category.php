<?php

namespace console\models;

/**
 * Class Post
 * @package console\models
 * @property \common\models\Category new
 */
class Category extends \common\models\old\OldCategory
{
    public function getNew()
    {
        return $this->hasOne(\common\models\Category::class, ['old_id' => 'id']);
    }
}
