<?php

namespace frontend\models;

use common\models\Tag;

class TagProvider extends Tag
{

    /**
     * @param int $limit
     * @return \yii\data\ActiveDataProvider
     */
    public function getProvider($limit = 10)
    {
        return PostProvider::getPostsByTag($this, $limit);
    }
}