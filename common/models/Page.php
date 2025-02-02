<?php

namespace common\models;

use common\components\Config;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Class Page
 * @property string title
 * @property string content
 * @property string slug
 * @property string type
 * @property string status
 * @property string old_id
 * @property mixed  image
 * @package common\models
 */
class Page extends MongoModel
{
    public    $search;
    protected $_translatedAttributes = ['title', 'content'];

    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';

    const TYPE_PAGE = 'page';
    const TYPE_BLOCK = 'block';
    const TYPE_SLIDE = 'slide';

    public static function getStatusArray()
    {
        return [
            self::STATUS_DRAFT     => __('Draft'),
            self::STATUS_PUBLISHED => __('Published'),
        ];
    }

    public static function getTypeArray()
    {
        return [
            self::TYPE_PAGE  => __('Page'),
            self::TYPE_BLOCK => __('Block'),
        ];
    }

    public function getViewUrl($scheme = true)
    {
        return Yii::$app->viewUrl
            ->createAbsoluteUrl(['page/view', 'slug' => $this->slug], $scheme);
    }

    public static function getStaticBlock($id, $object = false)
    {
        $res = self::findOne(['status' => self::STATUS_PUBLISHED, 'slug' => $id, 'type' => self::TYPE_BLOCK]);
        if ($object) {
            return ($res) ? $res : false;
        }
        return strval(($res) ? $res->content : __('Block {block} not found', ['block' => "<code>$id</code>"]));
    }


    public function getStatusLabel()
    {
        $status = self::getStatusArray();
        return isset($status[$this->status]) ? $status[$this->status] : $this->status;
    }

    public function getTypeLabel()
    {
        $status = self::getTypeArray();
        return isset($status[$this->type]) ? $status[$this->type] : $this->type;
    }

    public static function collectionName()
    {
        return 'page';
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'title',
            'type',
            'content',
            'slug',
            'status',
            'image',
            'old_id',
        ]);
    }

    public function rules()
    {
        return [
            [['status'], 'in', 'range' => array_keys(self::getStatusArray())],
            [['type'], 'in', 'range' => array_keys(self::getTypeArray())],
            [['title'], 'string', 'max' => 255],
            [['content', 'image'], 'safe'],
            [['title', 'slug'], 'required', 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE]],
            [['search'], 'safe', 'on' => self::SCENARIO_SEARCH],
            ['status', 'default', 'value' => self::STATUS_DRAFT],
            ['type', 'default', 'value' => self::TYPE_PAGE],
            [['slug'], 'match', 'skipOnEmpty' => false, 'pattern' => '/^[a-z0-9-]{3,255}$/', 'message' => __('Use URL friendly character')],
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => 30,
                                                   ],
                                               ]);

        $this->load($params);
        if ($this->search) {
            $query->orFilterWhere(['title' => ['$regex' => $this->search, '$options' => 'si']]);
            foreach (Config::getLanguageCodes() as $code) {
                $query->orFilterWhere(['_translations.title_' . $code => ['$regex' => $this->search, '$options' => 'si']]);
            }
        }

        return $dataProvider;
    }

    public function searchByType($type)
    {
        $query = self::find()->where(['type' => $type]);

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => 5,
                                                   ],
                                               ]);

        return $dataProvider;
    }

    public function afterDelete()
    {
        if ($image = $this->image) {
            $dir = Yii::getAlias('@static/uploads');
            if (isset($image['path']) && file_exists($dir . DS . $image['path'])) {
                unlink($dir . DS . $image['path']);
            }
        }
        parent::afterDelete();
    }

    /**
     * @param $link
     * @return null|Page
     */
    public static function findByLink($link)
    {
        return self::findOne(['status' => self::STATUS_PUBLISHED, 'slug' => $link, 'type' => self::TYPE_BLOCK]);
    }

    public function getCroppedImage($width = 870, $height = 260)
    {
        if ($this->image) {
            return parent::getCropImage($this->image, $width, $height, ManipulatorInterface::THUMBNAIL_OUTBOUND);
        }

        return false;
    }

    /**
     * @return Page[]
     */
    public static function getSliders()
    {
        return self::find()
                   ->where(['type' => self::TYPE_SLIDE, 'status' => self::STATUS_PUBLISHED])
                   ->addOrderBy(['updated_at' => -1])
                   ->limit(4)
                   ->all();
    }


    public function updatePage()
    {
        if (Yii::$app->language == Config::LANGUAGE_UZBEK) {
            foreach ($this->_translatedAttributes as $attribute) {
                if ($this->isAttributeChanged($attribute)) {
                    $this->{$attribute} = $this->convertLatinQuotas($this->{$attribute});
                }
            }
        }

        return $this->save();
    }

}