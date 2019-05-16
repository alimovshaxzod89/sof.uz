<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

/**
 * Class User
 * @property string $password
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $password_reset_date
 * @property string $access_token
 * @property string $access_token_date
 * @package common\models
 */
class GcmUser extends MongoModel
{
    public function attributes()
    {
        return [
            '_id',
            'token',
            'success',
            'fail',
            'created_at',
            'updated_at',
        ];
    }


    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'gcm_user';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => $this->getTimestampValue(),
            ],
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => 50,
                                                   ],
                                               ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        if ($this->search) {
            $query->orFilterWhere(['like', 'token', $this->search]);
        }

        return $dataProvider;
    }
}
