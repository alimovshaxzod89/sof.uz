<?php

namespace common\models;

use common\components\Config;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class Ad
 * @package common\models
 * @property string    status
 * @property string    _post
 * @property boolean   fb
 * @property boolean   tg
 * @property boolean   tw
 * @property string    fb_status
 * @property string    tg_status
 * @property string    tw_status
 * @property Timestamp date
 * @property Post      post
 */
class AutoPost extends MongoModel
{
    protected $_integerAttributes = ['limit_click', 'limit_view'];
    protected $_booleanAttributes = ['fb', 'tg', 'tw'];
    protected $_idAttributes      = ['_post'];

    const STATUS_PENDING = 'pending';
    const STATUS_POSTED = 'posted';
    const PUBLISH_STATUS_OK = 'ok';
    const PUBLISH_STATUS_ERROR = 'error';

    public static function collectionName()
    {
        return 'auto_post';
    }

    public function init()
    {
        if ($this->isNewRecord) {
            $this->tg = true;
        }
        parent::init(); // TODO: Change the autogenerated stub
    }

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_POSTED  => __('Posted'),
        ];
    }

    public function getStatusLabel()
    {
        $options = self::getStatusOptions();
        return isset($options[$this->status]) ? $options[$this->status] : $this->status;
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            '_post',
            'date',
            'status',
            'fb',
            'fb_status',
            'tg',
            'tg_status',
            'tw',
            'tw_status',
            'an',
            'an_status',
        ]);
    }

    public function rules()
    {
        return [
            [['_post', 'date'], 'required'],
            [['status', 'tw', 'tg', 'fb', 'search'], 'safe']
        ];
    }


    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPost()
    {
        return $this->hasOne(Post::class, ['_id' => '_post']);
    }


    public function beforeSave($insert)
    {
        if (is_numeric($this->date)) {
            $this->date = new Timestamp(1, intval($this->date));
        }

        if ($this->isNewRecord) {
            $this->status = self::STATUS_PENDING;
        }

        return parent::beforeSave($insert);
    }


    public function getDateFromSeconds()
    {
        return $this->date instanceof Timestamp ? $this->date->getTimestamp() : $this->date;
    }

    public function getUpdatedAtFormatted()
    {
        return Yii::$app->formatter->asDatetime($this->updated_at->getTimestamp(), 'php:d/M, H:i');
    }


    public static function publishAutoPublishPosts($final)
    {
        /**
         * @var $post AutoPost
         */
        $posts = self::find()
                     ->where([
                                 'status' => self::STATUS_PENDING,
                                 'date'   => ['$lte' => new Timestamp(1, time())],
                             ])
                     ->all();

        foreach ($posts as $post) {
            if ($final) {
                if ($post->publish()) {
                    echo "PUBLISHED: ->";
                }
            }

            echo $post->post->title . PHP_EOL;
        }
    }

    public function publish()
    {
        $date   = new Timestamp(1, time());
        $result = ['status' => self::STATUS_POSTED, 'updated_at' => $date];
        foreach (['tg' => 'telegram', 'tw' => 'twitter', 'an' => 'android'] as $attribute => $sharer) {
            if ($this->$attribute) {
                try {
                    $result["{$attribute}_status"] = $this->post->shareTo($sharer);
                } catch (\Exception $e) {
                    $result["{$attribute}_status"] = $e->getMessage();
                }
            }
        }

        return $this->updateAttributes($result);
    }

    public function isLocked()
    {
        return $this->status == self::STATUS_POSTED;
    }


    public function search($params = [], $type = false)
    {
        $this->load($params);
        $queryData = self::find();


        if ($this->search) {

            $query = Post::find()
                         ->select(['_id']);

            $query->orFilterWhere(['title' => ['$regex' => $this->search, '$options' => 'si']]);
            foreach (['uz', 'oz'] as $code) {
                $query->orFilterWhere(['_translations.title_' . $code => ['$regex' => $this->search, '$options' => 'si']]);
            }
            $query->orFilterWhere(['slug' => ['$regex' => $this->search, '$options' => 'si']]);

            $queryData->where(['_post' => $query->column()]);
        }


        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $queryData,
                                                   'sort'       => [
                                                       'defaultOrder' => [
                                                           'date' => SORT_DESC,
                                                       ],
                                                   ],
                                                   'pagination' => [
                                                       'pageSize' => 30,
                                                   ],
                                               ]);

        return $dataProvider;
    }


}