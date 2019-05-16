<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/1/17
 * Time: 5:28 PM
 */

namespace common\components;


use common\models\Comment;
use common\models\Post;
use yii\base\BootstrapInterface;
use yii\base\Event;

/**
 * Class BootstrapEvents bootstrapping events
 * @package common\components
 */
class BootstrapEvents implements BootstrapInterface
{
    public function bootstrap($app)
    {
        //Event::on(Comment::class, Comment::EVENT_AFTER_INSERT, [Post::class, 'onComment']);
        //Event::on(Comment::class, Comment::EVENT_AFTER_UPDATE, [Post::class, 'onComment']);
        //Event::on(Comment::class, Comment::EVENT_AFTER_DELETE, [Post::class, 'onComment']);
    }

}