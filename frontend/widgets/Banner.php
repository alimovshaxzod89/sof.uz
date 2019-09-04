<?php

namespace frontend\widgets;

use common\models\Place;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Html;
use yii\helpers\Json;

class Banner extends BaseWidget
{
    public    $place;
    public    $type;
    public    $options     = [];
    public    $excludePath = ['site/error'];
    protected $mode;

    public function init()
    {
        if (empty($this->place)) {
            throw new InvalidArgumentException('The property $place must be set.');
        }

        $this->options['id']    = $this->id;
        $classes                = isset($this->options['class']) ? $this->options['class'] : '';
        $this->options['class'] = $classes . ' banner-' . $this->place;
        Html::addCssClass($this->options, 'banner');
        parent::init();
    }

    public function run()
    {
        $place = Place::findOne(['slug' => $this->place, 'status' => Place::STATUS_ENABLE]);
        $path  = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

        if (in_array($path, $this->excludePath) || $place == null) {
            return '';
        }

        $view = $this->getView();
        $id   = $this->id;
        $view->registerJs("jQuery('#$id').initBanner({$this->getClientOptions()})");
        return Html::tag('div', '', $this->options);
    }

    protected function getClientOptions()
    {
        $options = [
            'place'    => $this->place,
            'language' => \Yii::$app->language,
        ];
        return Json::encode($options);
    }
}