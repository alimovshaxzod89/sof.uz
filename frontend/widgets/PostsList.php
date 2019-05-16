<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 12/13/17
 * Time: 1:35 AM
 */

namespace frontend\widgets;

use common\models\Currency;

class PostsList extends BaseWidget
{
    public $limit;

    public function init()
    {
        if (empty($this->limit)) {
            $this->limit = \Yii::$app->request->get('cl', 1);
        }

        parent::init();
    }

    public function run()
    {
        $rates = Currency::find()->orderBy(['created_at' => SORT_DESC])->limit($this->limit)->all();
        return $this->render('sidebarCurrency', compact('rates'));
    }
}