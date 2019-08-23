<?php

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