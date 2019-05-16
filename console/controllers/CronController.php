<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace console\controllers;

use common\models\Customer;
use common\models\Post;
use common\models\product\model\_Model;
use common\models\Review;
use common\models\Vendor;
use Yii;
use yii\console\Controller;
use yii\mongodb\Connection;

/**
 * This command working with crontab
 * @since 2.0
 */
class CronController extends Controller
{
    /**
     * This command running hourly cron jobs.
     */
    public function actionHourly()
    {

    }

    /**
     * This command running daily cron jobs.
     */
    public function actionDaily()
    {

    }

    /**
     * This command running weekly cron jobs.
     */
    public function actionWeekly()
    {

    }

}
