<?php

namespace frontend\widgets;

use frontend\models\PostProvider;

class HomePostLatest extends BaseWidget
{
    public $exclude;
    public $limit = 5;

    public function init()
    {
        $this->emptyText = __('This list is empty.');
    }

    public function run()
    {
        return $this->render('homePostLatest', [
            'news' => PostProvider::getLastPosts($this->limit, $this->exclude),
        ]);
    }
}