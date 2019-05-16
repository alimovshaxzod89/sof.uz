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