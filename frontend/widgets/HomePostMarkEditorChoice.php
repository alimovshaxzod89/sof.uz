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

class HomePostMarkEditorChoice extends BaseWidget
{
    public        $limit   = 3;
    public static $postIds = [];

    public function run()
    {
        $posts = PostProvider::getEditorsChoice($this->limit, array_merge(HomePostTop::$postIds, SidebarPost::$postIds));

        self::$postIds = ArrayHelper::map($posts, 'id', '_id');

        $content = $this->render('homePostMarkEditorChoice', [
            'posts' => $posts,
        ]);

        return $content;
    }
}