<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace console\models;

use common\models\Admin;
use MongoDB\BSON\Timestamp;
use yii\mongodb\ActiveQuery;

/**
 * Class Post
 * @package console\models
 */
class Category extends \common\models\Category
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'tax_id',
            '_parent',
        ]);
    }

    public function toMongo()
    {
        $cats = [];
        $tags = [];
        foreach ($this->relations as $relation) {
            foreach ($relation as $rt) {
                $tags[] = $rt->_id;
            }
            foreach ($relation as $rc) {
                $cats[] = $rc->_id;
            }
        }
        $this->updateAttributes([
                                    'title'        => $this->post_title,
                                    'url'          => $this->post_name,
                                    'status'       => $this->normalizedStatus(),
                                    'created_at'   => self::getTimestamp($this->post_date),
                                    'published_on' => self::getTimestamp($this->post_date),
                                    'updated_at'   => self::getTimestamp($this->post_modified),
                                    '_author'      => ($admin = Admin::findOne(['ID' => $this->post_author])) ? $admin->_id : $this->post_author,
                                    'content'      => $this->post_content,
                                    'info'         => $this->post_excerpt,
                                    'type'         => Post::TYPE_NEWS,
                                    '_tags'        => $tags,
                                    '_categories'  => $cats,
                                ]);
    }

    public static function normalContent($content)
    {
        preg_match('#(?:http?s://)?(?:www\.)?(?:youtube\.com/(?:v/|watch\?v=)|youtu\.be/)([\w-]+)(?:\S+)?#', $content, $match);
        if (count($match) && isset($match[1]) && !empty($match[1])) {
            $embed   = "<iframe width='100%' height='400' src='http://www.youtube.com/embed/$match[1]?autoplay=0' frameborder='0' allowfullscreen></iframe>";
            $content = str_replace($match[0], $embed, $content);
        }

        $content = preg_replace_callback(
            '#\[(contact-form-7)(.+?)?\](?:(.+?)?\[\/(contact-form-7)\])?#',
            function ($matches) {
                return '[contact]';
            },
            $content
        );

        $content = str_replace(
            ['www.terabayt.uz/wp-content/uploads/', 'terabayt.uz/wp-content/uploads/'],
            $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? ['static.tb.lc/uploads/', 'static.tb.lc/uploads/'] : ['static.terabayt.uz/uploads/', 'static.terabayt.uz/uploads/'],
            $content
        );
        return $content;
    }

    private static function getTimestamp($time)
    {
        $datetime = new \DateTime($time);
        return new Timestamp(1, ($datetime->getTimestamp() > 0) ? $datetime->getTimestamp() : time());
    }

    public function getPosts()
    {
        return Post::find()
                   ->where(['_categories' => ['$elemMatch' => ['$in' => [$this->_id]]]])
                   ->all();
    }
}
