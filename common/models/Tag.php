<?php

namespace common\models;

use common\components\Config;
use common\components\Translator;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class Tag
 * @package common\models
 * @property string  name
 * @property string  slug
 * @property string  old_id
 * @property integer count_l5d
 * @property integer count
 * @property Post[]  posts
 */
class Tag extends MongoModel
{
    protected $_booleanAttributes    = ['is_topic'];
    protected $_integerAttributes    = ['count'];
    protected $_searchableAttributes = ['name', 'slug'];

    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';

    public static function getTagsAsOption()
    {
        return array_map(function (Tag $tag) {
            return [
                'v' => $tag->getId(),
                't' => $tag->name,
            ];
        }, Tag::find()->where([])->limit(20)->addOrderBy(['count_l5d' => SORT_DESC])->all());
    }

    public static function createTag($name)
    {
        if ($name = trim($name)) {
            $slug = Translator::getInstance()->translateToLatin($name);
            $slug = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($slug)), '-');

            $old = self::find()
                       ->orFilterWhere(['name' => ['$regex' => $name, '$options' => 'si']])
                       ->orFilterWhere(['slug' => ['$regex' => $name, '$options' => 'si']])
                       ->one();
            if ($old) {
                return $old->_id;
            }

            $tag = new Tag();

            if (Yii::$app->language == Config::LANGUAGE_CYRILLIC) {
                $tag->name = Translator::getInstance()->translateToLatin($name);
            } else if (Yii::$app->language == Config::LANGUAGE_UZBEK) {
                $attr                      = self::getLanguageAttributeCode('name');
                $tag->_translations[$attr] = Translator::getInstance()->translateToCyrillic($name);
            } else {
                foreach (Config::getLanguageCodes() as $code) {
                    $attr                      = self::getLanguageAttributeCode('name', $code);
                    $tag->_translations[$attr] = $name;
                }
            }

            $tag->slug      = $slug;
            $tag->count_l5d = 0;

            if ($tag->save()) {
                return $tag->_id;
            }
        }

        return null;
    }

    /**
     * @param $text
     * @return array|\yii\mongodb\ActiveRecord
     */
    public static function searchTags($text = false)
    {
        $tags = Tag::find()
                   ->select(['name', 'count', 'slug'])
                   ->orderBy(['count' => SORT_DESC])
                   ->limit(20);

        if ($text) {
            foreach (Config::getLanguageCodes() as $code) {
                $text2 = (new Translator())->translateToLatin($text);
                $tags->orFilterWhere(['name_' . $code => ['$regex' => $text, '$options' => 'si']]);
                $tags->orFilterWhere(['name_' . $code => ['$regex' => $text2, '$options' => 'si']]);
            }
        }

        return $tags->all();
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'name',
            'slug',
            'count',
            'old_id',
            'count_l5d',
            'is_topic',
        ]);
    }

    public static function collectionName()
    {
        return 'tag';
    }

    public function rules()
    {
        return [
            [['count'], 'default', 'value' => 0],
            [['name', 'slug'], 'required', 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE]],
            [['slug'], 'unique', 'targetAttribute' => ['slug', 'name'], 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE]],
            [['slug', 'is_topic'], 'safe'],
            [['is_topic'], 'default', 'value' => false],
            [['search'], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @param     $params
     * @param int $pageSize
     * @return ActiveDataProvider
     */
    public function dataProvider($params, $pageSize = 10)
    {
        $query = self::find();


        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => $pageSize,
                                                   ],
                                                   'sort'       => [
                                                       'defaultOrder' => 'is_topic',
                                                   ],
                                               ]);

        $this->load($params);

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()
                     ->addOrderBy(['is_topic' => -1]);

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'sort'       => [
                                                       'defaultOrder' => [
                                                           'count_l5d' => SORT_DESC
                                                       ],
                                                   ],
                                                   'pagination' => [
                                                       'pageSize' => 30,
                                                   ],
                                               ]);

        $this->load($params);
        if ($this->search) {
            $query->orFilterWhere(['name' => ['$regex' => $this->search, '$options' => 'si']]);
            foreach (Config::getLanguageCodes() as $code) {
                $query->orFilterWhere(['_translations.name_' . $code => ['$regex' => $this->search, '$options' => 'si']]);
            }
        }

        return $dataProvider;
    }

    public function afterDelete()
    {
        /**
         * @var $post Post
         */
        $posts = Post::find()->all();
        foreach ($posts as $post) {
            $tags = $post->getConvertedTags();
            if (in_array($this->getId(), $tags)) {
                if (($key = array_search($this->_id, $tags)) !== false) {
                    unset($tags[$key]);
                    $post->updateAttributes(['_tags' => $tags]);
                }
            }
        }
    }

    public function beforeSave($insert)
    {
        if (!preg_match('/^[a-z][-a-z0-9]*$/', $this->slug)) {
            $slug       = Translator::getInstance()->translateToLatin($this->name);
            $this->slug = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($slug)), '-');
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return Post[]
     */
    public function getPosts()
    {
        return Post::find()
                   ->where([
                               '_tags' => [
                                   '$elemMatch' => [
                                       '$eq' => $this->_id
                                   ]
                               ]
                           ])
                   ->all();
    }

    public function getViewUrl($scheme = false)
    {
        return Url::to(['tag/' . $this->slug], $scheme);
    }

    public static function indexAllTags()
    {
        /**
         * @var $post Post
         * @var $tag  Tag
         */

        $posts = Post::find()
                     ->select(['_id', '_tags'])
                     ->where(['status' => Post::STATUS_PUBLISHED])
                     ->orderBy(['published_on' => SORT_DESC])
                     ->all();

        self::updateAll(['count' => 0]);

        foreach ($posts as $post) {
            foreach ($post->getTags() as $tag) {
                $tag->updateCounters(['count' => 1]);
            }
        }
    }

    public static function indexTrendingTags()
    {
        echo "indexTrendingTags===================\n";
        /**
         * @var $post Post
         * @var $tag  Tag
         */
        Post::getCollection()->createIndex(['published_on' => -1]);

        $time  = new Timestamp(1, time() - 5 * 24 * 3600);
        $posts = Post::find()
                     ->select(['_tags'])
                     ->where([
                                 'status'       => Post::STATUS_PUBLISHED,
                                 'published_on' => ['$gt' => $time],
                             ])
                     ->orderBy(['published_on' => SORT_DESC])
                     ->all();

        self::updateAll(['count_l5d' => 0]);
        if (is_array($posts) && count($posts)) {
            foreach ($posts as $post) {
                foreach ($post->getTags() as $tag) {
                    $tag->updateCounters(['count_l5d' => 1]);
                }
            }
        }
    }

    /**
     * @param int $limit
     * @return self|array|\yii\mongodb\ActiveRecord
     */
    public static function getTrending($limit = 10)
    {
        return self::find()
                   ->where(['count' => ['$gt' => 0]])
                   ->orderBy(['count_l5d' => SORT_DESC])
                   ->limit($limit)
                   ->all();
    }

    /**
     * @param int $limit
     * @return self|array|\yii\mongodb\ActiveRecord
     */
    public static function getMostUsed($limit = 10)
    {
        return self::find()
                   ->where(['count' => ['$gt' => 3]])
                   ->orderBy(['count' => SORT_DESC])
                   ->limit($limit)
                   ->all();
    }
}