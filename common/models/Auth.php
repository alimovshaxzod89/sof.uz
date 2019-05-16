<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace common\models;

use common\components\Config;
use Yii;
use yii\base\Exception;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\Html;
use yii\web\IdentityInterface;

/**
 * Class User
 * @property string $source_login
 * @property string $source_id
 * @property string source
 * @property string $_user
 * @property string $created_at
 * @property string $updated_at
 * @property User  user
 */
class Auth extends MongoModel
{
    public function attributes()
    {
        return [
            '_id',
            'source',
            'source_login',
            'source_id',
            '_user',
            'created_at',
            'updated_at',
        ];
    }

    public static function collectionName()
    {
        return '_auth';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['_id' => '_user']);
    }
}
