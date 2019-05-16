<?php

use frontend\components\View;
use frontend\widgets\SidebarWeather;

/**
 * @var $this       View
 */
$this->title = Yii::$app->name;
?>


<?= SidebarWeather::Widget() ?>