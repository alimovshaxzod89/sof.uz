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
class PostRelation extends \common\models\MongoModel
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'object_id',
            'term_taxonomy_id',
        ]);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::class, ['tax_id' => 'term_taxonomy_id']);
    }


    public function getCats()
    {
        return $this->hasMany(Category::class, ['tax_id' => 'term_taxonomy_id']);
    }
}
