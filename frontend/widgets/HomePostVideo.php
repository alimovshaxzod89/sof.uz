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