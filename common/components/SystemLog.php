<?php

namespace common\components;

use common\models\Admin;
use common\models\MongoModel;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "system_log".
 * @property integer $id
 * @property integer $_admin
 * @property integer $admin_name
 * @property string  $action
 * @property string  $type
 * @property string  $message
 * @property string  $get
 * @property string  $post
 * @property string  $query
 * @property integer $model_id
 * @property string  $ip
 * @property string  $x_ip
 * @property string  $created_at
 * @property array   event
 * @property string  method
 */
class SystemLog extends MongoModel
{
    public $search;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'system_log';
    }

    public function attributes()
    {
        return [
            '_id',
            '_admin',
            'admin_name',
            'action',
            'query',
            'get',
            'post',
            'type',
            'message',
            'method',
            'ip',
            'x_ip',
            'created_at',
        ];
    }

    public function rules()
    {
        return [
            ['search', 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'value'      => $this->getTimestampValue(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }


    public static function captureAction($message = null)
    {
        $log = new static();

        if (!Yii::$app->user->isGuest) {
            $log->_admin     = Yii::$app->user->identity->getId();
            $admin           = User::findOne($log->_admin);
            $log->admin_name = $admin ? $admin->getFullname() : "";
        }

        $log->message = $message;
        $log->method  = Yii::$app->request->getMethod();

        $id          = Yii::$app->request->get('id');
        $log->action = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id . '/' . $id;

        $log->ip    = Yii::$app->request->getUserIP();
        $log->query = Yii::$app->request->getQueryString();

        $log->get  = Yii::$app->request->get();
        $log->post = Yii::$app->request->post();

        $log->x_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        $log->save();
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'sort'       => [
                                                       'defaultOrder' => ['created_at' => SORT_DESC],
                                                   ],
                                                   'pagination' => [
                                                       'pageSize' => 10,
                                                   ],
                                               ]);
        $this->load($params);

        if ($this->search) {
            $query->orFilterWhere(['like', 'admin_name', $this->search]);
            $query->orFilterWhere(['like', '_admin', $this->search]);
            $query->orFilterWhere(['like', 'message', $this->search]);
            $query->orFilterWhere(['like', 'action', $this->search]);
            $query->orFilterWhere(['like', 'ip', $this->search]);
        }

        return $dataProvider;
    }
}
