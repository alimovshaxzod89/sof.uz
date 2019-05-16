<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use backend\widgets\checkbo\CheckBoAsset;
use common\models\Poll;
use yii\base\Widget;
use yii\helpers\Url;

CheckBoAsset::register(Yii::$app->view);
/**
 * @var $poll Poll
 * @var $this Widget
 */
?>
<div id="poll_widget_wrapper" data-url="<?= Url::to(['poll/view', 'id' => $poll->getId()], true) ?>"></div>