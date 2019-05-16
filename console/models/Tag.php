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
class Tag extends \common\models\Tag
{
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'tax_id',
        ]);
    }


    public function getPosts()
    {
        return Post::find()
                   ->where(['status' => Post::STATUS_PUBLISHED, '_tags' => ['$elemMatch' => ['$in' => [$this->_id]]]])
                   ->all();
    }
}
