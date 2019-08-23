<?php

namespace frontend\widgets;

use common\models\Rating;

class SidebarRating extends BaseWidget
{
    public $limit = 8;

    public $exclude;

    public function init()
    {
        $this->emptyText = __('Ratings not found');
    }

    public function run()
    {
        $ratings = Rating::find()
                         ->where(['status' => Rating::STATUS_ACTIVE])
                         ->andWhere(['_id' => ['$nin' => [$this->exclude]]])
                         ->limit($this->limit)
                         ->orderBy(['views' => SORT_DESC])
                         ->all();

        return $this->render('sidebarRating', [
            'models' => $ratings,
        ]);
    }
}