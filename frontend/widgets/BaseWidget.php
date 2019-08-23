<?php

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