<?php


namespace frontend\components;


use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\QueryInterface;

class WithoutCountActiveDataProvider extends ActiveDataProvider
{
    protected function prepareTotalCount()
    {
        return 10000;
    }
}