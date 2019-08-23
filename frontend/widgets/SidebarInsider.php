<?php

namespace frontend\widgets;


use frontend\models\PostProvider;

class SidebarInsider extends BaseWidget
{

    public $post;
    public $next;

    public function init()
    {
        $posts = PostProvider::getInsiderPosts(3);

        if (count($posts))
            $this->post = $posts[rand(0, count($posts) - 1)];
    }

    public function run()
    {
        if ($this->post) {
            return $this->render('sidebarInsider', [
                'post' => $this->post,
            ]);
        }
        return '';
    }
}