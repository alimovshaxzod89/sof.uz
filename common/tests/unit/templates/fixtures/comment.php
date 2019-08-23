<?php

use common\models\Comment;
use common\models\Post;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
$post = $faker->randomElement(array_map(function (Post $post) {
    return $post->getId();
}, Post::findAll(['status' => Post::STATUS_PUBLISHED])));
return [
    'text'    => $faker->text(64),
    'status'  => $faker->randomElement(array_keys(\common\models\Comment::getStatusArray())),
    '_post'   => $post,
    '_user'   => $faker->randomElement(array_map(function (\common\models\User $user) {
        return $user->getId();
    }, \common\models\User::find()->all())),
    '_parent' => $faker->randomElement(array_map(function (Comment $comment) {
        return $comment->getId();
    }, \common\models\Comment::find()->where(['_post' => $post])->all())),  // generate a sentence with 7 words
];