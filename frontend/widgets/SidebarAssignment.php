<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/10/17
 * Time: 11:00 PM
 */

namespace frontend\widgets;


use common\models\Poll;
use yii\base\InvalidParamException;
use yii\base\Widget;

class SidebarAssignment extends BaseWidget
{
    public function init()
    {
        $this->emptyText = __('Not found.');
    }

    public function run()
    {
        return $this->render('sidebarAssignment');
    }
}