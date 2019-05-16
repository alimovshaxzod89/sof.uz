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


use common\models\Blogger;
use frontend\models\AuthorProvider;
use frontend\models\PostProvider;
use yii\helpers\ArrayHelper;

class SidebarPopular extends BaseWidget
{
    public function init()
    {
        $this->emptyText = __('Popular posts not found.');
    }

    public function run()
    {
        $models = PostProvider::getPopularPosts(5);
        $post   = ArrayHelper::remove($models, 0);
        return $this->render('sidebarPopular', [
            'models' => $models,
            'post'   => $post,
        ]);
    }
}