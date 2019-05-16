<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace common\models;

use GuzzleHttp\Client;
use MongoDB\BSON\Timestamp;
use PHPHtmlParser\Dom;
use Yii;
use yii\data\ActiveDataProvider;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "system_dictionary".
 * @property integer   $id
 * @property string    $title
 * @property string    $url
 * @property Timestamp published_on
 * @property Timestamp created_at
 * @property Timestamp updated_at
 * @property ObjectId  $_id
 */
class PostBBC extends MongoModel
{
    public $search;


    public function attributes()
    {
        return ['_id', 'title', 'url', 'created_at', 'updated_at', 'published_on'];
    }

    public static function collectionName()
    {
        return 'system_dictionary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['url'], 'url'],
            [['url'], 'unique'],
            [['title', 'search'], 'safe'],
        ];
    }

    public function search($params)
    {
        $this->load($params);

        $query = self::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'sort'       => [
                                                       'defaultOrder' => [
                                                           'created_at' => SORT_DESC,
                                                       ],
                                                   ],
                                                   'pagination' => [
                                                       'pageSize' => 50,
                                                   ],
                                               ]);

        if ($this->search) {
            $query->orFilterWhere(['like', 'title', $this->search]);
            $query->orFilterWhere(['like', 'url', $this->search]);
        }

        return $dataProvider;
    }

    public function getId()
    {
        return (string)$this->_id;
    }

    public function beforeSave($insert)
    {
        $this->title = trim(strip_tags($this->title));

        if (is_numeric($this->published_on)) {
            $this->published_on = new Timestamp(1, intval($this->published_on));
        }

        if ($this->isNewRecord) {
            $this->fetchData();
        }

        return parent::beforeSave($insert);
    }

    public function fetchData()
    {
        $client = new Client([]);

        if ($result = $client->get($this->url)) {
            $content = $result->getBody();

            $dom = new Dom();
            $dom->load($content);

            $tag = $dom->find('meta[property=og:title]', 0);;

            $this->title = trim($tag->content);

        }
    }

    /**
     * @param int $limit
     * @return PostBBC[]
     */
    public static function getLast($limit = 3)
    {
        return self::find()
                   ->orderBy(['updated_at' => SORT_DESC])
                   ->limit($limit)
                   ->all();
    }
}
