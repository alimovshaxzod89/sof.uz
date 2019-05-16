<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace console\models;

use common\models\Admin;
use MongoDB\BSON\Timestamp;
use yii\db\Query;
use yii\helpers\Url;

/**
 * Class Post
 * @package console\models
 */
class Post extends \common\models\Post
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'post_date',
            'post_modified',
            'post_content',
            'post_excerpt',
            'post_status',
            'post_name',
            'post_author',
            'ID',
            'post_title',
            'guid',
            'comment_count',
            'post_lang_choice',
            'post_parent',
        ]);
    }

    public function toMongo()
    {
        $cats = [];
        $tags = [];
        foreach ($this->relations as $relation) {
            foreach ($relation->tags as $rt) {
                $tags[] = $rt->_id;
            }
            foreach ($relation->cats as $rc) {
                $cats[] = $rc->_id;
            }
        }
        $gallery = [];
        $files   = (new Query())->from('wp_posts')
                                ->where(['post_type' => 'attachment'])
                                ->andWhere(['post_parent' => $this->ID])
                                ->all();
        foreach ($files as $file) {
            $gallery[] = [
                'base_url' => \Yii::getAlias('@staticUrl/uploads'),
                'name'     => $file['post_name'] ?? '',
                'order'    => key($files),
                'type'     => $file['post_mime_type'] ?? '',
                'path'     => substr($file['guid'], strpos($file['guid'], 'uploads') + 8),
                'caption'  => [
                    'cy' => $file['post_content'],
                ],
            ];
        }

        $image_meta = (new Query())->from('wp_postmeta')
                                   ->where(['meta_key' => '_thumbnail_id', 'post_id' => $this->ID])
                                   ->one();
        $image      = ($f = WPPost::findOne(['ID' => $image_meta['meta_value']]))
            ? [
                'base_url' => \Yii::getAlias('@staticUrl/uploads'),
                'name'     => $f->post_name,
                'type'     => $f->post_mime_type,
                'path'     => substr($f->guid, strpos($f->guid, 'uploads') + 8),
                'caption'  => [
                    'cy' => $f->post_content,
                ],
            ]
            : [];

        $view = (new Query())
            ->from('wp_popularpostsdata')
            ->select('pageviews')
            ->where(['postid' => $this->ID])
            ->one();

        $this->updateAttributes([
                                    'title'        => $this->post_title,
                                    'url'          => ($pos = strrpos($this->post_name, '-k') !== false)
                                        ? substr($this->post_name, 0, strrpos($this->post_name, '-k')) : $this->post_name,
                                    'status'       => $this->normalizedStatus(),
                                    'created_at'   => self::getTimestamp($this->post_date),
                                    'published_on' => self::getTimestamp($this->post_date),
                                    'updated_at'   => self::getTimestamp($this->post_modified),
                                    '_author'      => ($admin = Admin::findOne(['_old' => $this->post_author])) ? $admin->_id : $this->post_author,
                                    'content'      => self::normalContent($this->post_content),
                                    'info'         => $this->post_excerpt,
                                    'type'         => (\count($gallery) > 1) ? Post::TYPE_GALLERY : Post::TYPE_NEWS,
                                    '_tags'        => $tags,
                                    '_categories'  => $cats,
                                    '_domain'      => $this->domain->domain->domain ?? $this->_domain,
                                    'views'        => $view ? $view['pageviews'] : 0,
                                    'gallery'      => (\count($gallery) > 1) ? $gallery : [],
                                    'image'        => $image,
                                    'has_gallery'  => \count($gallery) > 1,
                                    'short_id'     => $this->ID,
                                ]);
        $this->convertToLatin();
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

        $content = strtr($content, [
            'www.ayol.uz/wp-content'    => 'static.ayol.uz',
            'www.erkak.uz/wp-content'   => 'static.erkak.uz',
            'www.farzand.uz/wp-content' => 'static.farzand.uz',
        ]);
        return $content;
    }

    public function normalizedStatus()
    {
        if ($this->post_status == 'publish') {
            return self::STATUS_PUBLISHED;
        } elseif ($this->post_status == 'trash') {
            return self::STATUS_IN_TRASH;
        } elseif (strpos($this->post_status, 'draft') !== false) {
            return self::STATUS_DRAFT;
        }
        return self::STATUS_DISABLED;
    }

    private static function getTimestamp($time)
    {
        $datetime = new \DateTime($time);
        return new Timestamp(1, ($datetime->getTimestamp() > 0) ? $datetime->getTimestamp() : time());
    }

    public function getRelations()
    {
        return $this->hasMany(PostRelation::class, ['object_id' => 'ID']);
    }

    public function getDomain()
    {
        return $this->hasOne(WPPostDomains::class, ['post_id' => 'ID']);
    }

    public function getViewUrl(\common\models\Category $category = null, $domain = '')
    {
        if ($category) {
            return $category->slug . '/' . $this->url;
        }
        return 'post/' . $this->url;
    }
}
