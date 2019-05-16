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

class HomePostLatest extends BaseWidget
{
    public $exclude;
    public $limit = 5;

    public function init()
    {
        $this->emptyText = __('This list is empty.');
    }

    public function run()
    {
        return $this->render('homePostLatest', [
            'news' => PostProvider::getLastPosts($this->limit, $this->exclude),
        ]);
    }
}