<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/12/17
 * Time: 12:22 AM
 */

namespace frontend\widgets;


use frontend\components\View;
use yii\base\Widget;

/**
 * Class BaseWidget
 * @package frontend\widgets
 * @property View $view
 */
class BaseWidget extends Widget
{
    public $emptyText;

    public function getImageUrl($name)
    {
        return $this->view->getImageUrl($name);
    }

}