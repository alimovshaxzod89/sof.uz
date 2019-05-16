<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\components\Config;
use common\models\Admin;
use common\models\Category;
use common\models\Poll;
use common\models\Tag;
use Faker\Provider\File;
use yii\helpers\FileHelper;

/**
 * Created by PhpStorm.
 * Date: 12/6/17
 * Time: 4:31 PM
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'question' => $faker->text(64),
    'status'   => $faker->randomElement(array_keys(Poll::getStatusArray())),
    'answers'  => $faker->randomElements([
                                             $faker->text(16),
                                             $faker->text(16),
                                             $faker->text(16),
                                             $faker->text(16),
                                             $faker->text(16),
                                             $faker->text(16),
                                         ], mt_rand(2, 6)),
    'expire_time'    => $faker->dateTimeInInterval()->getTimestamp(),
];