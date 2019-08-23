<?php

use common\models\Poll;

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