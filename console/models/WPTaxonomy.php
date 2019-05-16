<?php

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
 * @property WPTerms[] term
 */
class WPTaxonomy extends WPBase
{
    const TYPE_TAG        = 'post_tag';
    const TYPE_CAT        = 'category';
    const TYPE_TAG_REVIEW = 'review-brand';
    const TYPE_CAT_REVIEW = 'review-category';

    public static function tableName()
    {
        return '{{%term_taxonomy}}';
    }


    public function getTerm()
    {
        return $this->hasOne(WPTerms::class, ['term_id' => 'term_id']);
    }
}