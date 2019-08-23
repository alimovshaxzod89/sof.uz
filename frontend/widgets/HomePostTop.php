<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/11/17
 * Time: 9:59 PM
 */

namespace frontend\widgets;


use common\models\Post;
use frontend\models\PostProvider;
use yii\helpers\ArrayHelper;

class HomePostTop extends BaseWidget
{
    public        $mark    = Post::LABEL_REGULAR;
    public        $limit   = 3;
    public static $postIds = [];

    public function run()
    {
        $big   = PostProvider::getTopPost();
        $posts = PostProvider::getByMark(Post::LABEL_IMPORTANT, $this->limit + ($big && mb_strlen($big->info) > 200 ? 1 : 0),[$big->_id]);

        self::$postIds                = ArrayHelper::map($posts, 'id', '_id');
        self::$postIds[$big->getId()] = $big->_id;

        $content = $this->render('homePostTop', [
            'posts' => $posts,
            'big'   => $big,
        ]);

        return $content;
    }
}