<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/27/17
 * Time: 7:05 PM
 */

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
    protected $mode;
    public    $options     = [];
    public    $excludePath = ['site/error','page/view'];

    public function init()
    {
        if (empty($this->place)) {
            throw new InvalidArgumentException('The property $place must be set.');
        }

        $this->options['id']    = $this->id;
        $this->options['class'] = 'banner-' . $this->place;
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