<?php

namespace frontend\widgets;

use frontend\models\PostProvider;
use yii\helpers\ArrayHelper;

class SidebarPost extends BaseWidget
{
    static public $postIds = [];

    public function init()
    {
        $this->emptyText = __('Posts not found');
    }

    public function run()
    {
        $posts         = PostProvider::getTopPosts(6);
        self::$postIds = ArrayHelper::map($posts, 'id', '_id');

        return $this->render('sidebarPost', [
            'reads' => $posts,
        ]);
    }
}