<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/11/17
 * Time: 9:59 PM
 */

namespace frontend\widgets;


use common\models\Post;
use frontend\models\PostProvider;
use yii\base\InvalidParamException;
use yii\base\Widget;

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