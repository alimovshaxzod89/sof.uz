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


use common\models\Post;
use frontend\models\PostProvider;
use yii\base\InvalidParamException;
use yii\base\Widget;

class HomeSidebarQuestion extends BaseWidget
{
    public function init()
    {
        $this->emptyText = __('Questions not found');
    }

    public function run()
    {
        return $this->render('homeSidebarQuestion', [
            'questions' => []
        ]);
    }
}