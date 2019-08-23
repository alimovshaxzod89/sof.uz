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

class SidebarImportant extends BaseWidget
{
    static public $postIds = [];

    public function init()
    {
        $this->emptyText = __('Posts not found');
    }

    public function run()
    {
        $posts = PostProvider::getSidebarImportant(6, []);

        return $this->render('sidebarImportant', [
            'reads' => $posts,
        ]);
    }
}