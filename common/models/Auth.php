<?php

namespace common\models;

/**
 * Class User
 * @property string $source_login
 * @property string $source_id
 * @property string source
 * @property string $_user
 * @property string $created_at
 * @property string $updated_at
 * @property User   user
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
