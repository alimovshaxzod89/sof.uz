<?php

namespace api\models\v1;

use common\models\Tag as TagModel;


class Tag extends TagModel
{
    public function fields()
    {
        return [
            'id',
            'name',
            'count' => function () {
                return intval($this->count);
            },
        ];
    }
}
