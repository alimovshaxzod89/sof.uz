<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\components\Config;
use common\models\Admin;
use common\models\Category;
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
$pass = $faker->password();
return [
    'fullname'     => $faker->firstName . ' ' . $faker->lastName,
    'login'        => $faker->userName,
    'status'       => $faker->randomElement(array_keys(\common\models\User::getStatusOptions())),
    'password'     => Yii::$app->security->generatePasswordHash($pass),  // generate a sentence with 7 words
    'confirmation' => Yii::$app->security->generatePasswordHash($pass),  // generate a sentence with 7 words
    'email'        => $faker->email,
];