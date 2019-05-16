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
 * @property WPTaxonomy taxonomy
 */
class WPTaxonomyRelation extends WPBase
{
    const TYPE_TAG        = 'post_tag';
    const TYPE_CAT        = 'category';
    const TYPE_TAG_REVIEW = 'review-brand';
    const TYPE_CAT_REVIEW = 'review-category';

    public static function tableName()
    {
        return '{{%term_relationships}}';
    }

    public function getTaxonomy()
    {
        return $this->hasOne(WPTaxonomy::class, ['term_taxonomy_id' => 'term_taxonomy_id'])->with('term');
    }

}