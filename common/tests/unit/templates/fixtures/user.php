<?php

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