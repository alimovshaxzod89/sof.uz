<?php

namespace frontend\widgets;

use common\models\Post;
use frontend\models\PostProvider;

class HomePostGallery extends BaseWidget
{
    public function init()
    {
        $this->emptyText = __('Posts not found');
    }

    public function run()
    {
        return $this->render('homePostGallery', [
            'gallery' => PostProvider::getByType(Post::TYPE_GALLERY, 3),
            'info'   => PostProvider::find()->where(['status' => Post::STATUS_PUBLISHED, 'label' => Post::LABEL_REGULAR])->andWhere(['has_info' => ['$eq' => true]])->orderBy(['published_on' => -1])->one(),
        ]);
    }
}