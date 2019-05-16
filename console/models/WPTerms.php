<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace console\models;

use common\models\MongoModel;
use common\models\Tag;
use common\models\Category;
use yii\mongodb\Query;

/**
 * Class WPTaxonomy
 * @property string term_id
 * @property string name
 * @property string slug
 * @property string term_group
 * @property string term_order
 */
class WPTerms extends WPBase
{
    const TYPE_TAG        = 'post_tag';
    const TYPE_CAT        = 'category';
    const TYPE_TAG_REVIEW = 'review-brand';
    const TYPE_CAT_REVIEW = 'review-category';

    public static function tableName()
    {
        return '{{%terms}}';
    }

    public static function convert()
    {
        if (function_exists('get_terms')) {
            Category::deleteAll();
            Tag::deleteAll();
            $new = new Category(['scenario' => 'insert', 'title' => 'Тавсиф', '_slug' => 'tavsif']);
            if ($new->save()) {
                printf("\"%s\" Category converted successfully.\n", $new->title);
            } else {
                var_dump('Тавсиф');
                var_dump($new->errors);
            }

            self::termLoop(self::TYPE_CAT);
            self::termLoop(self::TYPE_CAT_REVIEW);
            self::termLoop(self::TYPE_TAG);
            self::termLoop(self::TYPE_TAG_REVIEW);
        }
    }

    public static function termLoop($type, $old = 0, $new = 0)
    {
        $terms = self::getTerms($type, $old);
        if (count($terms)) {
            foreach ($terms as $term) {
                self::setNew($term, $type, $new);
            }
        }
    }

    public static function setNew($term, $type, $id = 0)
    {
        if ($type == self::TYPE_CAT || $type == self::TYPE_CAT_REVIEW) {
            $rv   = $type == self::TYPE_CAT_REVIEW;
            $slug = MongoModel::generateSlug($term->name);
            $c    = Category::findOne(['_slug' => 'tavsif']);

            $new = new Category([
                                    'scenario'    => Category::SCENARIO_INSERT,
                                    'title'       => $term->name,
                                    'description' => $term->description,
                                    '_old'        => $term->term_id,
                                    '_guide'      => $term->slug,
                                    '_slug'       => $rv ? ('tavsif-' . $slug) : $slug,
                                    '_parent'     => $rv && is_object($c) ? $c->getId() : $id,
                                ]);

            if ($new->save()) {
                printf("\"%s\" Category converted successfully.\n", $new->title);
                self::termLoop($type, $term->term_id, $new->getId());
            } else {
                var_dump($term->name);
                var_dump($new->errors);
            }
        }

        if ($type == self::TYPE_TAG || $type == self::TYPE_TAG_REVIEW) {
            $new = new Tag([
                               'scenario' => Tag::SCENARIO_INSERT,
                               'title'    => $term->name,
                               '_slug'    => Tag::generateSlug($term->name),
                               '_old'     => $term->term_id,
                               '_guide'   => $term->slug,
                           ]);

            if ($new->save()) {
                printf("\"%s\" Tag converted successfully.\n", $new->title);
            } else {
                var_dump($term->name);
                var_dump($new->errors);
            }
        }
    }

    public static function getTerms($type, $id = 0)
    {
        return get_terms(['taxonomy' => $type, 'parent' => $id]);
    }
}