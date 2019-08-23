<?php

use common\components\Config;
use common\models\Category;
use common\models\Tag;

/**
 * Created by PhpStorm.
 * Date: 12/6/17
 * Time: 4:31 PM
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'title'       => $faker->text(32),
    'url'         => $faker->slug,
    '_categories' => $faker->randomElements(array_map(function (Category $cat) {
        return $cat->getId();
    }, Category::findAll(['parent' => Config::get(Config::CONFIG_CATALOG_POST_ROOT)])), mt_rand(1, 3)),
    '_tags'       => $faker->randomElements(array_map(function (Tag $tag) {
        return $tag->getId();
    }, Tag::find()->all()), mt_rand(1, 4)),
    'type'        => $faker->randomElement(['news', 'video', 'gallery']),  // generate a sentence with 7 words
    'info'        => $faker->sentence(15, true),  // generate a sentence with 7 words
    'content'     => $faker->randomHtml(),
];