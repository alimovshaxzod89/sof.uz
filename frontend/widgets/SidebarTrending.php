<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/18/17
 * Time: 1:48 AM
 */

namespace frontend\widgets;

use frontend\models\PostProvider;
use frontend\models\PostQuery;

class SidebarTrending extends BaseWidget
{
    /** @var PostQuery */
    public $posts;
    public $limit;

    public function init()
    {
        if (!$this->posts) {
            $this->posts = PostProvider::find()
                                       ->active()
                                       ->limit($this->limit)
                                       ->orderBy(['views_l7d' => SORT_DESC]);
        }
    }

    public function run()
    {
        if ($this->posts) {
            return $this->render('sidebarTrending', [
                'posts' => $this->posts,
            ]);
        }
        return '';
    }
}