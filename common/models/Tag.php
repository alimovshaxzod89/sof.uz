<?php

namespace common\models;

use common\components\Config;
use common\components\Translator;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Application;

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
    protected $_translatedAttributes = ['name'];
    protected $_searchableAttributes = ['name', 'slug'];

    public $name_uz;
    public $name_oz;

    public static function getTagsAsOption()
    {
        $all = Tag::find()->where([])->limit(20)->addOrderBy(['count_l5d' => SORT_DESC])->all();
        return array_map(function (Tag $tag) {
            return [
                'v' => $tag->getId(),
                't' => $tag->name,
            ];
        }, $all);
    }

    public static function createTag($name)
    {
        if ($name = trim($name)) {

            $trans = Translator::getInstance();

            $slug = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($trans->translateToLatin($name))), '-');

            $old = self::find()
                       ->orFilterWhere(['name' => ['$eq' => $name]])
                       ->orFilterWhere(['slug' => ['$eq' => $slug]])
                       ->one();
            if ($old) {
                return $old->_id;
            }

            $tag       = new Tag();
            $tag->name = $name;
            $tag->setTranslation('name', $trans->translateToLatin($name), Config::LANGUAGE_UZBEK);
            $tag->setTranslation('name', $trans->translateToCyrillic($name), Config::LANGUAGE_CYRILLIC);


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
        $tags  = Tag::find()
                    ->orderBy(['count' => SORT_DESC])
                    ->limit(20);
        $trans = Translator::getInstance();

        if ($text) {
            $lat = $trans->translateToLatin($text);
            $cyr = $trans->translateToCyrillic($text);

            foreach (Config::getLanguageCodes() as $code) {
                $tags->orFilterWhere(['_translations.name_' . $code => ['$regex' => $text, '$options' => 'si']]);
                $tags->orFilterWhere(['_translations.name_' . $code => ['$regex' => $lat, '$options' => 'si']]);
                $tags->orFilterWhere(['_translations.name_' . $code => ['$regex' => $cyr, '$options' => 'si']]);
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
            [['name_oz', 'name_uz', 'slug'], 'required', 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE]],
            [['slug'], 'unique', 'targetAttribute' => ['slug', 'name'], 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE]],
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
            $query->orFilterWhere(['_translations.name_uz' => ['$regex' => $this->search, '$options' => 'si']]);
            $query->orFilterWhere(['_translations.name_oz' => ['$regex' => $this->search, '$options' => 'si']]);
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

        if (Yii::$app instanceof Application) {
            if ($this->name_uz) {
                $this->setTranslation('name', $this->name_uz, Config::LANGUAGE_UZBEK);
            }
            if ($this->name_oz) {
                $this->setTranslation('name', $this->name_oz, Config::LANGUAGE_CYRILLIC);
            }
        }

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->name_uz = $this->getTranslation('name', Config::LANGUAGE_UZBEK);
        $this->name_oz = $this->getTranslation('name', Config::LANGUAGE_CYRILLIC);

        parent::afterFind();
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
        return Yii::$app->viewUrl
            ->createAbsoluteUrl(['category/tag', 'slug' => $this->slug], $scheme);
    }

    public static function indexAllTags1()
    {
        echo "indexAllTags\n";
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

    public static function indexAllTags()
    {
        echo "indexAllTags\n";
        /**
         * @var $post Post
         * @var $tag  Tag
         */

        self::updateAll(['count' => 0]);

        foreach (self::find()->all() as $tag) {
            $c = Post::find()
                     ->select(['_id'])
                     ->where([
                                 'status' => Post::STATUS_PUBLISHED,
                                 '_tags'  => [
                                     '$elemMatch' => [
                                         '$eq' => $tag->_id
                                     ]
                                 ]
                             ])
                     ->count();
            $tag->updateAttributes(['count' => $c]);
        }
    }

    public static function indexTrendingTags()
    {
        echo "indexTrendingTags===================\n";
        /**
         * @var $post Post
         * @var $tag  Tag
         */

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

    public static function translateAllTags()
    {
        Yii::$app->language = Config::LANGUAGE_CYRILLIC;

        $translator = Translator::getInstance();
        /**
         * @var  $item Tag
         */
        $slugs = [];
        foreach (self::find()->orderBy(['count' => SORT_DESC])->all() as $item) {
            $trans               = $item->_translations;
            $trans['name_uz']    = $translator->translateToLatin($item->name);
            $trans['name_oz']    = $item->name;
            $item->slug          = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($trans['name_uz'])), '-');
            $item->_translations = $trans;

            if ($item->save(false)) {
                echo $item->name_uz . PHP_EOL;
            }

            if (!isset($slugs[$item->slug])) {
                $slugs[$item->slug] = [];
            }

            $slugs[$item->slug][] = $item;
        }

        foreach ($slugs as $slug => $items) {
            if (count($items) > 1) {
                $main = array_pop($items);

                foreach ($items as $item) {
                    foreach ($item->posts as $post) {
                        $tags = $post->getConvertedTags();
                        foreach ($tags as $i => $t) {
                            if ((string)$t == $item->id) {
                                unset($tags[$i]);
                                break;
                            }
                        }
                        $tags[] = $main->_id;
                        $post->updateAttributes(['_tags' => array_values($tags)]);
                    }

                    if (Tag::deleteAll(['_id' => $item->_id])) {
                        echo $item->name . PHP_EOL;
                    }
                }
            }
        }
    }
}