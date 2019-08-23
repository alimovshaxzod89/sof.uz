<?php

namespace frontend\widgets;


use common\models\Post;
use frontend\models\PostProvider;

class HomePostVideo extends BaseWidget
{
    public function init()
    {
        $this->emptyText = __('Videos not found');
    }

    public function run()
    {
        return $this->render('homePostVideo', [
            'posts' => PostProvider::getByType(Post::TYPE_VIDEO, 6)
        ]);
    }
}