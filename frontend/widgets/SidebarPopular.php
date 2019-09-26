<?php

namespace frontend\widgets;

use frontend\models\PostProvider;
use yii\helpers\ArrayHelper;

class SidebarPopular extends BaseWidget
{
    public function init()
    {
        $this->emptyText = __('Popular posts not found.');
    }

    public function run()
    {
        $models = PostProvider::getPopularPosts();
        $post   = ArrayHelper::remove($models, 0);
        return $this->render('sidebarPopular', [
            'models' => $models,
            'post'   => $post,
        ]);
    }
}