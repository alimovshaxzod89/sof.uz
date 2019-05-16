<?php

use common\components\Config;
use yii\base\Event;
use yii\web\Controller;

error_reporting(E_ALL ^ E_WARNING);

Event::on(Controller::className(), Controller::EVENT_BEFORE_ACTION, function ($event) {

});
