<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

namespace api\models\v1;

use common\models\Blogger;


class Author extends Blogger
{
    public function fields()
    {
        return [
            'id',
            'full_name'    => function () {
                return implode('#|#', $this->getAllTranslations('full_name'));
            },
            'job'         => function () {
                return implode('#|#', $this->getAllTranslations('job'));
            },
            'intro'       => function () {
                return implode('#|#', $this->getAllTranslations('intro'));
            },
            'description' => function () {
                return implode('#|#', $this->getAllTranslations('description'));
            },
            'posts'       => function () {
                return array_map(function (Post $post) {
                    return $post->getId();
                }, $this->getArticles()->where(['status' => Post::STATUS_PUBLISHED])->all());
            },
            'email',
            'facebook',
            'count_posts' => function () {
                return $this->getArticles()->where(['status' => Post::STATUS_PUBLISHED])->count();
            },
        ];
    }

    public function getArticles()
    {
        return $this->hasMany(Post::className(), ['_author' => 'id']);
    }

}
