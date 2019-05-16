<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/10/17
 * Time: 11:00 PM
 */

namespace frontend\widgets;


use common\models\Post;
use frontend\models\PostProvider;

class SidebarAudioPlay extends BaseWidget
{
    public $postId;
    public $post;
    public $next;

    public function init()
    {
        $posts = PostProvider::getRandomAudios(10);

        if (count($posts))
            $this->post = $posts[rand(0, count($posts) - 1)];
    }

    public function run()
    {
        if ($this->post) {
            return $this->render('sidebarAudio', [
                'post' => $this->post,
            ]);
        }

        return '';
    }
}