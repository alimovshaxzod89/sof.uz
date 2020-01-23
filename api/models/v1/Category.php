<?php

namespace api\models\v1;

use common\models\Category as CategoryModel;
use MongoDB\BSON\Timestamp;


class Category extends CategoryModel
{
    public function fields()
    {
        return [
            'id',
            'name'       => function () {
                return implode('#|#', $this->getAllTranslations('name'));
            },
            'slug',
            'created_at' => function () {
                return ($this->created_at instanceof Timestamp) ? $this->created_at->getTimestamp() : $this->created_at;
            },
            'child',
            'is_menu'    => function () {
                return boolval($this->is_menu);
            },
        ];
    }
}
