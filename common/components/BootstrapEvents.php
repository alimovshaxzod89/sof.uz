<?php

namespace common\components;


use yii\base\BootstrapInterface;

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