<?php

namespace common\fixtures;


use yii\mongodb\ActiveFixture;
use yii\mongodb\ActiveRecord;

class PollFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Poll';

    public function load()
    {
        //$this->resetCollection();
        $this->data = [];
        $data       = $this->getData();
        if (empty($data)) {
            return;
        }
        foreach ($data as $alias => $row) {
            $class = $this->modelClass;
            /** @var ActiveRecord $model */
            $model = new $class($row);
            $model->save();
            $this->data[$alias] = $row;
        }
    }
}