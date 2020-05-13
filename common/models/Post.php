<?php

namespace common\models;

use backend\components\sharer\BaseShare;
use common\components\Config;
use common\components\Translator;
use DateTime;
use Imagine\Image\ManipulatorInterface;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Timestamp;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\AbstractNode;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * Class Post
 * @property string title
 * @property string title_color
 * @property string content
 * @property string content_source
 * @property string slug
 * @property string status
 * @property string old_id
 * @property string old_slug
 * @property integer old_views
 * @property array image
 * @property array image_source
 * @property array image_caption
 * @property array image_size
 * @property string mobile_image
 * @property string mobile_thumb
 * @property array gallery_items
 * @property string audio_url
 * @property string video_url
 * @property string template
 * @property string _author_post
 * @property mixed info
 * @property mixed type
 * @property mixed label
 * @property mixed _tags
 * @property mixed _similar
 * @property mixed gallery
 * @property mixed _categories
 * @property Category[] categories
 * @property Category category
 * @property Tag[] tags
 * @property mixed audio
 * @property mixed audio_duration
 * @property mixed audio_duration_formatted
 * @property mixed video
 * @property mixed youtube_url
 * @property mixed mover_url
 * @property mixed auto_publish_time
 * @property Timestamp published_on
 * @property mixed updated_on
 * @property mixed creator_type
 * @property mixed _creator
 * @property mixed _author
 * @property string creator_session
 * @property integer short_id
 * @property boolean has_video
 * @property boolean has_gallery
 * @property boolean has_info
 * @property boolean is_main
 * @property boolean is_instant
 * @property boolean is_sidebar
 * @property boolean is_mobile
 * @property Timestamp pushed_on
 * @property Timestamp locked_on
 * @property integer ad_time
 * @property boolean hide_image
 * @property boolean is_ad
 * @property integer views
 * @property integer views_l3d
 * @property integer views_l7d
 * @property integer views_l30d
 * @property integer views_today
 * @property integer read_min
 * @property boolean img_watermark
 * @property Admin creator
 * @property Admin author
 * @package common\models
 */
class Post extends MongoModel
{
    const SCENARIO_CONVERT = 'convert';
    const AUTHOR_CATEGORY = '5d63dd4d18855a227578b4ab';
    protected $_translatedAttributes = ['title', 'content', 'info', 'audio', 'image_source'];
    protected $_booleanAttributes = ['img_watermark', 'is_ad', 'has_video', 'has_gallery', 'has_info', 'is_sidebar', 'is_main', 'is_instant', 'is_mobile', 'hide_image'];
    protected $_integerAttributes = ['ad_time', 'views', 'template', 'read_min', 'views_l3d', 'views_l7d', 'views_l30d', 'views_today'];
    protected $_searchableAttributes = ['title', 'info', 'category'];
    protected $_idAttributes = ['_creator', '_author'];

    const LABEL_REGULAR = 'regular';
    const LABEL_IMPORTANT = 'important';
    const LABEL_CREATOR_CHOICE = 'creator_choice';
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_DISABLED = 'disabled';
    const STATUS_AUTO_PUBLISH = 'auto_publish';
    const STATUS_IN_TRASH = 'in_trash';

    const TYPE_NEWS = 'news';
    const TYPE_GALLERY = 'gallery';
    const TYPE_VIDEO = 'video';

    const SCENARIO_NEWS = 'news';
    const SCENARIO_GALLERY = 'gallery';
    const SCENARIO_VIDEO = 'video';
    const SCENARIO_CREATE = 'create';

    const SOCIAL_FACEBOOK = 'facebook';
    const SOCIAL_TWITTER = 'twitter';
    const SOCIAL_TELEGRAM = 'telegram';
    const SOCIAL_ANDROID = 'android';

    public $user;
    public $post_type;
    public $image_caption;
    public $category;
    public $has_updates;
    public $language;

    public static function collectionName()
    {
        return 'post';
    }

    public static function cyLatAttributes()
    {
        return [
            'title',
            'info',
            'content',
            'image_source',
        ];
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'title',
            '_categories',
            'info',
            'status',
            'content',
            'slug',
            'type',
            '_author',
            '_creator',
            'creator_type',

            'auto_publish_time',
            'published_on',
            'updated_on',
            'image',
            'label',
            '_tags',
            '_related',
            '_similar',
            '_author_post',

            'title_color',
            'image_source',
            'image_size',
            'content_source',
            'audio',
            'audio_duration',
            'audio_duration_formatted',
            'video',
            'youtube_url',
            'mover_url',
            'gallery',
            'mobile_image',
            'mobile_thumb',
            'gallery_items',

            'short_id',
            'has_video',
            'has_gallery',
            'has_info',
            'views_l3d',
            'views_l7d',
            'views_l30d',
            'views_today',
            'views',
            'is_main',
            'is_sidebar',
            'is_instant',
            'is_mobile',
            'is_ad',
            'ad_time',
            'ad_show',
            'old_id',
            'old_slug',
            'old_views',
            'pushed_on',
            'hide_image',
            'img_watermark',
            'read_min',
            'template',
            'locked_on',
            'creator_session',
        ]);
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        if ($this->slug) {
            $scenarios[self::SCENARIO_UPDATE] = '!slug';
        }

        return $scenarios;
    }

    public function rules()
    {
        return [
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [['views', 'has_updates'], 'default', 'value' => 0],
            [['type'], 'in', 'range' => array_keys(self::getTypeArray())],

            [['slug'], 'unique'],
            [['template'], 'number', 'integerOnly' => true],
            [['info', 'image_source', 'content_source'], 'string', 'max' => 500],
            [['info', 'image_source', 'content_source'], 'safe', 'on' => self::SCENARIO_CREATE],

            [['title', 'info', 'slug', 'content', 'title_color', 'image', 'hide_image', 'status', 'label', 'gallery', 'info', '_categories', '_tags', 'audio', 'video', 'published_on', 'is_sidebar', 'is_main', 'is_instant', '_creator', '_author'],
                'safe', 'on' => [self::SCENARIO_NEWS, self::SCENARIO_CONVERT, self::SCENARIO_GALLERY, self::SCENARIO_VIDEO]],

            [['slug'], 'match', 'skipOnEmpty' => true, 'pattern' => '/^[a-z0-9-]{3,255}$/', 'message' => __('Use URL friendly character')],

            [['youtube_url'], 'validateYoutube', 'skipOnEmpty' => true, 'message' => __('Invalid Youtube url')],
            [['mover_url'], 'validateMover', 'skipOnEmpty' => true, 'message' => __('Invalid Mover url')],

            [['search', 'user', 'post_type'], 'safe', 'on' => 'search'],
            [$this->_booleanAttributes, 'safe'],

            [['title', 'slug', 'info', '_categories'], 'required',
                'on' => [self::SCENARIO_NEWS, self::SCENARIO_GALLERY, self::SCENARIO_VIDEO],
                'when' => function ($model) {
                    return $model->status != self::STATUS_DRAFT;
                }],

            [['title', 'slug'], 'required', 'on' => self::SCENARIO_CONVERT, 'when' => function ($model) {
                return $model->status != self::STATUS_DRAFT;
            }],

            [['content'], 'required',
                'on' => [self::SCENARIO_NEWS, self::SCENARIO_CONVERT],
                'when' => function ($model) {
                    return $model->status != self::STATUS_DRAFT && empty($this->audio);
                }],

            [['gallery'], 'required', 'on' => [self::SCENARIO_GALLERY], 'when' => function ($model) {
                return $model->status != self::STATUS_DRAFT;
            }],

            [['youtube_url'], 'required', 'on' => [self::SCENARIO_VIDEO], 'when' => function ($model) {
                return $model->status != self::STATUS_DRAFT && empty($model->mover_url);
            }],

            [['mover_url'], 'required', 'on' => [self::SCENARIO_VIDEO], 'when' => function ($model) {
                return $model->status != self::STATUS_DRAFT && empty($model->youtube_url);
            }],

            [['auto_publish_time'], 'required',
                'on' => [self::SCENARIO_GALLERY, self::SCENARIO_VIDEO, self::SCENARIO_NEWS],
                'whenClient' => 'checkAutoPublishStatus',
                'when' => function ($model) {
                    return $model->status == self::STATUS_AUTO_PUBLISH;
                }],

            [['status'], 'in', 'range' => array_keys(self::getStatusArray()),
                'on' => [self::SCENARIO_GALLERY, self::SCENARIO_VIDEO, self::SCENARIO_NEWS, self::SCENARIO_CONVERT]],

            [['title'], 'string', 'max' => 512],
            [['slug'], 'string', 'max' => 256],
            [['ad_time'], 'number', 'min' => 0, 'max' => 1000, 'integerOnly' => true],

            [['old_id', 'old_slug', 'old_views', 'is_ad', 'ad_time'], 'safe'],

            [['info'], 'string', 'min' => 50, 'max' => 500, 'on' => [self::SCENARIO_NEWS, self::SCENARIO_GALLERY, self::SCENARIO_VIDEO]],
            [['short_id'], 'safe',
                'on' => [self::SCENARIO_NEWS, self::SCENARIO_CONVERT, self::SCENARIO_GALLERY, self::SCENARIO_VIDEO],
                'when' => function ($model) {
                    return $model->status != self::STATUS_DRAFT;
                }],
        ];
    }

    public function validateYoutube($attribute, $options)
    {
        if ($value = $this->$attribute) {
            $parts = parse_url($value);
            if (isset($parts['host']) && isset($parts['path']) && isset($parts['query'])) {
                if (strpos($parts['host'], 'youtube.com') && $parts['path'] == '/watch' && strpos($parts['query'], 'v=') === 0) {
                    return true;
                }
            }
            $this->addError($attribute, __('Invalid youtube url'));
        }
    }


    public function validateMover($attribute, $options)
    {
        if ($value = $this->$attribute) {
            $parts = parse_url($value);

            if (isset($parts['host']) && isset($parts['path'])) {
                if ($parts['host'] == 'mover.uz' && strpos($parts['path'], '/watch') === 0) {
                    return true;
                }
            }
            $this->addError($attribute, __('Invalid mover url'));
        }
    }

    public static function getColorOptions()
    {
        return [
            '#000000' => __('Black'),
            '#ff0000' => __('Red'),
            '#00ff00' => __('Green'),
            '#0000ff' => __('Blue'),
        ];
    }

    public static function getStatusArray()
    {
        return [
            self::STATUS_DRAFT => __('Draft'),
            self::STATUS_PUBLISHED => __('Published'),
            self::STATUS_AUTO_PUBLISH => __('Auto Publish'),
            self::STATUS_DISABLED => __('Disabled'),
        ];
    }

    public static function getLabelArray($empty = false)
    {
        $options = [
            self::LABEL_REGULAR => __('Regular News'),
            self::LABEL_IMPORTANT => __('Important News'),
            self::LABEL_CREATOR_CHOICE => __('Creator\'s Choice'),
        ];
        return $empty ? ArrayHelper::merge(['' => ''], $options) : $options;
    }

    public static function getSocialArray()
    {
        return [
            self::SOCIAL_FACEBOOK => __('Facebook'),
            self::SOCIAL_TWITTER => __('Twitter'),
            self::SOCIAL_TELEGRAM => __('Telegram'),
            self::SOCIAL_ANDROID => __('Android'),
        ];
    }

    public static function getTypeArray()
    {
        return [
            self::TYPE_NEWS => __('News'),
            self::TYPE_GALLERY => __('Gallery'),
            self::TYPE_VIDEO => __('Video'),
        ];
    }

    public function getLabelLabel()
    {
        $status = self::getLabelArray();
        return isset($status[$this->label]) ? $status[$this->label] : $this->label;
    }

    public function getStatusLabel()
    {
        $status = self::getStatusArray();
        return isset($status[$this->status]) ? $status[$this->status] : $this->status;
    }

    public function search($params = [], $type = false)
    {
        $this->load($params);
        $query = self::find()
            ->with('categories');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => $this->status == self::STATUS_DRAFT ?
                    [
                        'created_at' => SORT_DESC,
                    ] : [
                        'published_on' => SORT_DESC,
                    ],
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);


        if ($this->search) {
            $query->orFilterWhere(['title' => ['$regex' => $this->search, '$options' => 'si']]);
            foreach (Config::getLanguageCodes() as $code) {
                $query->orFilterWhere(['_translations.title_' . $code => ['$regex' => $this->search, '$options' => 'si']]);
            }
            $query->orFilterWhere(['slug' => ['$regex' => $this->search, '$options' => 'si']]);
        }
        if ($this->status) {
            $query->andFilterWhere(['status' => $this->status]);
        }
        if ($this->post_type) {
            $query->andFilterWhere(['type' => $this->post_type]);
        }

        $query->andFilterWhere(['status' => ['$ne' => self::STATUS_IN_TRASH]]);

        return $dataProvider;
    }

    public function searchAuthorPosts($params = [])
    {
        $this->load($params);
        $query = self::find()
            ->with('categories');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'published_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);


        if ($this->search) {
            $query->orFilterWhere(['title' => ['$regex' => $this->search, '$options' => 'si']]);
            foreach (Config::getLanguageCodes() as $code) {
                $query->orFilterWhere(['_translations.title_' . $code => ['$regex' => $this->search, '$options' => 'si']]);
            }
            $query->orFilterWhere(['slug' => ['$regex' => $this->search, '$options' => 'si']]);
        }


        $query->andFilterWhere([
            '_author' => [
                '$in' => Admin::find()->select(['_id'])->column()
            ],
            '_categories' => [
                '$elemMatch' => ['$in' => ['5d63dd4d18855a227578b4ab']]
            ],
            'status' => ['$ne' => self::STATUS_IN_TRASH]
        ]);

        return $dataProvider;
    }


    public static function indexAuthorPosts()
    {
        self::updateAll(['_author_post' => null]);
        $items = self::find()
            ->select(['_id', '_author'])
            ->andFilterWhere([
                '_author' => [
                    '$in' => Admin::find()->select(['_id'])->column()
                ],
                '_categories' => [
                    '$elemMatch' => ['$in' => [self::AUTHOR_CATEGORY]]
                ],
                'status' => self::STATUS_PUBLISHED
            ])
            ->orderBy(['published_on' => SORT_DESC])
            ->asArray()
            ->all();

        $authors = [];


        foreach ($items as $k => $item) {
            $id = (string)$item['_author'];
            if (!isset($authors[$id])) {
                $authors[$id] = $item['_id'];
                self::updateAll(['_author_post' => $k], ['_id' => $item['_id']]);

                if (count($authors) > 10) {
                    break;
                }
            }
        }
    }

    public function searchTrash($params = [])
    {
        $this->load($params);
        $query = self::find()
            ->with('categories');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'published_on' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        if ($this->search) {
            foreach (Config::getLanguageCodes() as $code) {
                $query->orFilterWhere(['_translations.title_' . $code => ['$regex' => $this->search, '$options' => 'si']]);
            }
        }
        $query->andFilterWhere(['status' => ['$eq' => self::STATUS_IN_TRASH]]);

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
        if ($audio = $this->audio) {
            $dir = Yii::getAlias('@static/uploads');
            if (isset($audio['path']) && file_exists($dir . DS . $audio['path'])) {
                unlink($dir . DS . $image['path']);
            }
        }
        AutoPost::deleteAll(['_post' => $this->_id]);

        parent::afterDelete();
    }

    public function updatePost($force = false)
    {
        if ($this->status == self::STATUS_PUBLISHED && !$this->published_on) {
            $this->published_on = call_user_func($this->getTimestampValue());
        }

        if (is_numeric($this->published_on)) {
            $this->published_on = new Timestamp(1, intval($this->published_on));
        }

        $this->updated_on = call_user_func($this->getTimestampValue());


        if (is_numeric($this->auto_publish_time)) {
            $this->auto_publish_time = new Timestamp(1, intval($this->auto_publish_time));
        }

        $this->processGallery(false);
        $this->processContent($force);
        $this->prepareMobilePost($force);

        if (mb_strlen($this->info) > 500) {
            $this->info = mb_substr($this->info, 0, 500);
        }

        return $this->save();
    }

    public function beforeSave($insert)
    {
        if (!(Yii::$app instanceof \yii\console\Application)) {
            if (($this->isNewRecord || !$this->_creator) && Yii::$app->user->identity instanceof Admin) {
                $this->_creator = Yii::$app->user->identity->getId();
                //$this->_author  = Yii::$app->user->identity->getId();
            }
        }

        if (empty($this->slug) || $this->isNewRecord) {
            $slug = Translator::getInstance()->translateToLatin($this->title);
            $this->slug = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($slug)), '-');
        }

        if (!$this->short_id) {
            $attempt = 0;
            do {
                $attempt++;
                $code = self::offerRandomSequence(3 + round($attempt / 10));
                $this->short_id = $code;
            } while (self::find()->where(['short_id' => $code])->count() > 0);
        }

        if (is_string($this->_categories))
            $this->_categories = explode(',', $this->_categories);

        if (is_string($this->_tags)) {
            $this->_tags = $this->getConvertedTagsWithCreate();
        }

        $this->info = strip_tags($this->info);

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->author) {
            self::indexAuthorPosts();
        }
        parent::afterSave($insert, $changedAttributes);
    }


    private function calculateReadingTime()
    {
        $count = StringHelper::countWords($this->content . ' ' . $this->title);

        $count = $count / 150;

        $min = ceil(floor($count) + (($count - floor($count)) * 60 > 30 ? 1 : 0));

        return $min ?: 1;
    }

    private function processGallery($function)
    {
        if (is_array($this->gallery) && count($this->gallery)) {
            $files = [];
            foreach ($this->gallery as $item) {
                $item['order'] = intval($item['order']);
                //todo process images
                if ($function != null) {
                    $files[] = call_user_func($function, $item);
                } else {
                    $files[] = $item;
                }
            }
            $this->gallery = $files;
        }
    }

    public function getConvertedTagsWithCreate()
    {
        if (is_string($this->_tags)) {
            $tags = array_filter(explode(',', $this->_tags));

            if (count($tags) > 0)
                return array_filter(array_map(function ($id) {
                    if (preg_match('/[a-z0-9]{24}/', $id)) {
                        return new ObjectId(trim($id));
                    } else {
                        $id = Tag::createTag($id);
                        return $id;
                    }
                }, $tags));
        }

        return [];
    }

    public function getConvertedTags()
    {
        $tags = array_filter(explode(',', $this->_tags));

        if (count($tags) > 0)
            return array_map(function ($id) {
                return new ObjectId(trim($id));
            }, $tags);

        return [];
    }

    public function afterFind()
    {
        if (empty($this->category)) {
            $cats = $this->_categories;
            if (!empty($cats))
                $this->category = Category::getCached(array_pop($cats));
        }

        if (is_array($this->_categories))
            $this->_categories = implode(',', $this->_categories);

        if (is_array($this->_tags))
            $this->_tags = implode(',', $this->_tags);

        if ($this->image && is_array($this->image)) {
            $this->image_caption = $this->getImageCaption($this->image);
            $image = $this->image;
            $image['base_url'] = Yii::getAlias('@staticUrl/uploads');
            $this->image = $image;
        }

        if (is_numeric($this->published_on))
            $this->published_on = new Timestamp(1, $this->published_on);

        if (is_numeric($this->auto_publish_time))
            $this->auto_publish_time = new Timestamp(1, $this->auto_publish_time);

        parent::afterFind();
    }

    public function getImageCaption($image)
    {
        $lang = Config::getLanguageCode();
        if (isset($image['caption']) && isset($image['caption'][$lang])) {
            return $image['caption'][$lang];
        }
        return '';
    }

    public function processContent($force = false)
    {
        $this->title = trim($this->title);

        if (!$this->slug)
            $this->slug = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower((new Translator())->translateToLatin($this->title))), '-');

        $this->slug = trim($this->slug, ' -');
        $this->slug = str_replace('--', '-', $this->slug);
        $this->slug = str_replace('--', '-', $this->slug);

        $image = $this->image;

        if ($this->isAttributeChanged('image') || $force || is_array($image) && !isset($image['width'])) {
            if ($file = $this->getFilePath('image', true)) {
                if ($size = @getimagesize($file)) {
                    $image['width'] = $size[0];
                    $image['height'] = $size[1];

                    $this->image = $image;
                }
            }
        }

        if ($this->isAttributeChanged('content') || $force) {
            if ($content = $this->content) {
                $content = preg_replace_callback('#(<img\s(?>(?!src=)[^>])*?src=")data:image/(gif|png|jpeg|jpg);base64,([\w=+/]++)("[^>]*>)#', function ($matches) {
                    return $this->dataToImage($matches);
                }, $content);

                $content = preg_replace('/&nbsp;/i', ' ', $content);
                $content = preg_replace('/(<[^>]+) width=".*?"/i', '$1', $content);
                $content = preg_replace('/(<[^>]+) height=".*?"/i', '$1', $content);
                $content = preg_replace('/(<table[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $content);;
                $content = preg_replace('/(<tr[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $content);;
                $content = preg_replace('/(<td[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $content);;
                $content = preg_replace('/(<img[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $content);;
                $content = preg_replace('/(<span[^>]*) style=("[^"]+"|\'[^\']+\')([^>]*>)/i', '$1$3', $content);;

                $content = preg_replace("/\"\/\//i", '"https://', $content);
                $content = preg_replace("/'\/\//i", '\'https://', $content);


                $dom = new Dom();
                $dom->loadStr($content, ['enforceEncoding' => 'UTF-8']);

                /**
                 * @var $img AbstractNode
                 */
                /*$images = $dom->find('iframe');

                foreach ($images as $img) {
                    $parentClass = $img->getParent()->getAttribute('class');
                    if (strpos($parentClass, 'iframe-wrapper') === false) {
                        $img->getParent()->setAttribute('class', "$parentClass iframe-wrapper");
                    }
                }

                $images = $dom->find('img');

                foreach ($images as $img) {
                    $class = $img->getAttribute('class');

                    if ($img->getParent()->getTag()->name() != 'figure') {
                        continue;
                    }

                    $caption = $img->getParent()->find('figcaption');
                    foreach ($caption as $item) {
                        $item->setAttribute('class', 'wp-caption-text');
                        if (trim($item->text()) == '') {
                            $item->delete();
                        }
                    }

                    //TODO crop images fit to particular size
                    if ($class == '') {
                        $img->getParent()->setAttribute('class', 'image wp-caption alignnone');
                    }

                    if (strpos($class, 'size-full') !== false) {
                        $img->getParent()->setAttribute('class', 'image wp-caption alignbig');
                    }

                    if (strpos($class, 'size-half-left') !== false) {
                        $img->getParent()->setAttribute('class', 'image wp-caption alignleft');
                    }

                    if (strpos($class, 'size-half-right') !== false) {
                        $img->getParent()->setAttribute('class', 'image wp-caption alignright');
                    }

                }

                $content = (string)$dom;

                $this->content = $content;*/
            }
        }

        if (Yii::$app->language == Config::LANGUAGE_UZBEK) {
            foreach (self::cyLatAttributes() as $attribute) {
                if ($this->isAttributeChanged($attribute) || $force) {
                    $this->{$attribute} = $this->convertLatinQuotas($this->{$attribute});
                }
            }

            if ($data = $this->image) {
                if (is_array($data) && isset($data['caption']) && is_array($data['caption'])) {
                    if (isset($data['caption']['uz'])) {
                        $data['caption']['uz'] = $this->convertLatinQuotas($data['caption']['uz']);
                    }
                }
                $this->image = $data;
            }

            $this->processGallery(function ($item) {
                if (is_array($item) && isset($item['caption']) && isset($item['caption']['uz'])) {
                    $item['caption']['uz'] = $this->convertLatinQuotas($item['caption']['uz']);
                }
                return $item;
            });
        }
    }

    public function convertToLatin()
    {
        $translator = Translator::getInstance();

        foreach (self::cyLatAttributes() as $attribute) {
            $value = $this->getTranslation($attribute, Config::LANGUAGE_CYRILLIC);
            $this->setTranslation($attribute, $translator->translateToLatin($value), Config::LANGUAGE_UZBEK);
        }


        if ($data = $this->image) {
            if (is_array($data) && isset($data['caption']) && is_array($data['caption'])) {
                if (isset($data['caption'], $data['caption']['oz'])) {
                    $data['caption']['uz'] = $translator->translateToLatin($data['caption']['oz']);
                }
            }
        }

        $this->processGallery(function ($item) use ($translator) {
            if (is_array($item) && isset($item['caption']['oz'])) {
                $item['caption']['uz'] = $translator->translateToLatin($item['caption']['oz']);
            }
            return $item;
        });

        Yii::trace('Converted to Latin');

        return $this->updateAttributes(['_translations' => $this->_translations, 'image' => $data, 'gallery' => $this->gallery]);
    }

    public function convertToCyrillic()
    {
        $translator = Translator::getInstance();

        foreach (self::cyLatAttributes() as $attribute) {
            $value = $this->getTranslation($attribute, Config::LANGUAGE_UZBEK);
            $this->setTranslation($attribute, $translator->translateToCyrillic($value), Config::LANGUAGE_CYRILLIC);
        }

        if ($data = $this->image) {
            if (is_array($data) && isset($data['caption']) && is_array($data['caption'])) {
                if (isset($data['caption']['uz'])) {
                    $data['caption']['oz'] = $translator->translateToCyrillic($data['caption']['uz']);
                }
            }
        }

        $this->processGallery(function ($item) use ($translator) {
            if (is_array($item) && isset($item['caption']) && isset($item['caption']['uz'])) {
                $item['caption']['oz'] = $translator->translateToCyrillic($item['caption']['uz']);
            }
            return $item;
        });

        Yii::trace('Converted to Cyrillic');

        return $this->updateAttributes(['_translations' => $this->_translations, 'image' => $data, 'gallery' => $this->gallery]);
    }

    public function shareTo($social)
    {
        /** @var BaseShare $sharer */
        $sharer = Yii::$app->get($social);

        if ($social == Post::SOCIAL_ANDROID) {
            $sharer->publishIos($this);
        }

        return $sharer->publish($this);
    }

    public function isPublished()
    {
        return $this->status == self::STATUS_PUBLISHED;
    }

    public function getViewUrl(Category $category = null, $scheme = true)
    {
        if ($category instanceof Category)
            return Yii::$app->viewUrl
                ->createAbsoluteUrl(['post/view', 'slug' => $this->slug, 'category' => $category->slug], $scheme);

        return Yii::$app->viewUrl
            ->createAbsoluteUrl(['post/view', 'slug' => $this->slug], $scheme);
    }

    public function getAudioDurationFormatted()
    {
        if ($this->audio_duration) {
            if ($this->audio_duration < 60) {
                return "00:{$this->audio_duration}";
            } elseif ($this->audio_duration < 3600) {
                return floor($this->audio_duration / 60) . ":" . ($this->audio_duration % 60);
            } else {
                return floor($this->audio_duration / 3600) . ":" . floor(($this->audio_duration % 3600) / 60) . ":" . ($this->audio_duration % 60);
            }
        }
        return '...';
    }

    public static function getEmptyCroppedImage($width = 870, $height = 260)
    {
        return self::getCropImage([], $width, $height, ManipulatorInterface::THUMBNAIL_OUTBOUND);

    }

    public function getCroppedImage($width = 870, $height = 260, $manipulation = 1, $watermark = false)
    {
        $manipulation = $manipulation == 1 ? ManipulatorInterface::THUMBNAIL_OUTBOUND : ManipulatorInterface::THUMBNAIL_INSET;
        return self::getCropImage($this->image, $width, $height, $manipulation, $watermark);
    }

    public function getTagsData()
    {
        $tags = Tag::find()->where(['_id' => $this->getConvertedTags()])->all();
        if (count($tags)) {
            return array_map(function (Tag $tag) {
                return [
                    'v' => $tag->getId(),
                    't' => $tag->name,
                ];
            }, $tags);
        }

        return [];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['_id' => '_categories']);
    }

    /**
     * @return array|\yii\db\ActiveQueryInterface|\yii\mongodb\ActiveRecord
     */
    public function getTags()
    {
        return Tag::find()->where(['_id' => $this->getConvertedTags()])->all();
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getCreator()
    {
        return $this->hasOne(Admin::class, ['_id' => '_creator']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getAuthor()
    {
        return $this->hasOne(Admin::class, ['_id' => '_author']);
    }

    public function getTitleView()
    {
        if ($this->title) {
            return $this->getShortTitle();
        }

        return __('Draft post at {date}', [
            'date' => Yii::$app->formatter->asDatetime($this->created_at ? $this->created_at->getTimestamp() : time(), 'php: l, d-F H:i')
        ]);
    }

    public function getYoutubeEmbedUrl()
    {
        if ($url = $this->youtube_url) {
            $url = str_replace('watch?v=', 'embed/', $url);
            return $url;
        }

        return false;
    }

    public function getMoverEmbedUrl()
    {
        if ($url = $this->mover_url) {
            $url = str_replace('/watch/', '/video/embed/', $url);
            return $url;
        }

        return false;
    }

    public function toTrash()
    {
        $this->updateAttributes(['status' => self::STATUS_IN_TRASH]);
        return true;
    }

    public function restoreFromTrash()
    {
        $this->updateAttributes(['status' => self::STATUS_DRAFT]);
        return true;
    }

    public function getShortViewUrl($scheme = true)
    {
        return Yii::$app->viewUrl
            ->createAbsoluteUrl(['post/short', 'short' => $this->short_id], $scheme);
    }

    public function getPreviewUrl($scheme = true)
    {
        return Yii::$app->viewUrl
            ->createAbsoluteUrl(['post/preview', 'id' => $this->id, 'hash' => Yii::$app->security->generatePasswordHash($this->id)], $scheme);
    }

    public function getShortTitle()
    {
        $title = StringHelper::truncateWords($this->title, 6);

        if (strlen($title) > 40) {
            return StringHelper::truncate($title, 40);
        }
        return $title;
    }

    public function getShortFormattedDate($format = 'php:j.m.Y H:i')
    {
        if ($this->published_on instanceof Timestamp) {
            $diff = time() - $this->published_on->getTimestamp();

            if ($diff < 300) {
                return __('Hozirgina');
            } elseif ($diff < 3600) {
                return Yii::$app->formatter->asDate($this->published_on->getTimestamp(), 'php:H:i');
            } elseif ($diff < 86400) {
                $today = new DateTime();
                $today->setTime(0, 0, 0);

                $match_date = new DateTime();
                $match_date->setTimestamp($this->published_on->getTimestamp());
                $match_date->setTime(0, 0, 0);

                $diff = $today->diff($match_date);
                $diffDays = (integer)$diff->format("%R%a");
                switch ($diffDays) {
                    case 0:
                        //today
                        return __('Bugun, {time}', ['time' => Yii::$app->formatter->asDate($this->published_on->getTimestamp(), 'php:H:i')]);
                        break;
                    case -1:
                        //Yesterday
                        return __('Kecha, {time}', ['time' => Yii::$app->formatter->asDate($this->published_on->getTimestamp(), 'php:H:i')]);
                        break;
                }

                return Yii::$app->formatter->asDate($this->published_on->getTimestamp(), 'php:j.m H:i');
            } elseif ($diff < 31536000) {
                return Yii::$app->formatter->asDate($this->published_on->getTimestamp(), 'php:j.m H:i');
            }

            return Yii::$app->formatter->asDate($this->published_on->getTimestamp(), $format);
        }

        return Yii::$app->formatter->asDate($this->created_at instanceof Timestamp ? $this->created_at->getTimestamp() : $this->created_at);
    }

    public function getViewLabel()
    {
        return intval($this->views) + intval($this->old_views ?: 0);
    }

    /**
     * @return GalleryItem[]
     */
    public function getGalleryItemsModel()
    {
        $result = [];
        if (is_array($this->gallery)) {
            foreach ($this->gallery as $item) {
                $result[] = new GalleryItem($item);
            }
        }

        return $result;
    }

    public function getPublishedOnSeconds()
    {
        return $this->published_on instanceof Timestamp ? $this->published_on->getTimestamp() : $this->published_on;
    }

    public function getAutoPublishTimeSeconds()
    {
        return $this->auto_publish_time instanceof Timestamp ? $this->auto_publish_time->getTimestamp() : $this->auto_publish_time;
    }

    public static function publishAutoPublishPosts($final)
    {
        /**
         * @var $post Post
         */
        $posts = self::find()
            ->where([
                'status' => self::STATUS_AUTO_PUBLISH,
                'auto_publish_time' => ['$lte' => new Timestamp(1, time())],
            ])
            ->all();

        foreach ($posts as $post) {
            if ($final) {
                $date = new Timestamp(1, time());
                $post->updateAttributes(['status' => self::STATUS_PUBLISHED, 'updated_at' => $date, 'updated_on' => $date]);
                echo "PUBLISHED: ->";
            }

            echo $post->title . PHP_EOL;
        }
    }

    public static function indexAdPosts($final)
    {
        /**
         * @var $post Post
         */
        $posts = self::find()
            ->where([
                'status' => self::STATUS_PUBLISHED,
                'is_ad' => true,
                'ad_time' => ['$gt' => 0],
            ])
            ->all();


        foreach ($posts as $post) {
            if ($final) {
                $diff = (time() - $post->published_on->getTimestamp());
                if ($diff > $post->ad_time * 3600) {
                    if ($post->updateAttributes(['ad_time' => 0])) {
                        echo "AD UNPUBLISHED >> ";
                    }
                }
            }
            echo $post->title . PHP_EOL;
        }
    }

    public function hasCommonTags(Post $post)
    {
        $thisTag = is_string($this->_tags) ? explode(',', $this->_tags) : $this->_tags;
        $postTag = is_string($post->_tags) ? explode(',', $post->_tags) : $post->_tags;
        return count(array_intersect($thisTag, $postTag));
    }

    public function indexSimilarPosts($posts = false)
    {
        if (!$posts) {
            $posts = Post::find()
                ->where(['status' => self::STATUS_PUBLISHED])
                ->andFilterWhere(['_tags' => ['$in' => is_string($this->_tags) ? $this->getConvertedTags() : $this->_tags]])
                ->all();
        }


        $tagData = [];
        $similar = [];
        $counted = 0;

        for ($i = 0; $i < count($posts) && $counted < 150; $i++) {
            $postB = $posts[$i];
            $common = $this->hasCommonTags($postB);

            if ($common && $postB->id != $this->id) {
                $counted += $common;
                $tagData[] = [
                    'id' => $postB->_id,
                    'count' => $common,
                    'updated' => $postB->published_on->getTimestamp(),
                    'title' => $postB->title,
                ];
            }
        }

        if (count($tagData)) {
            uasort($tagData, function ($b, $a) {
                if ($a['count'] == $b['count']) {
                    return $a['updated'] - $b['updated'];
                }

                return $a['count'] - $b['count'];
            });

            $similar = array_values(array_column(array_slice($tagData, 0, 6, true), 'id'));
        }

        $this->_similar = $similar;

        return $similar;
    }

    public static function reindexSimilarPostsByTag()
    {
        echo "reindexSimilarPostsByTag===================\n";
        $start = microtime(true);
        /**
         * @var $postA Post
         * @var $postB Post
         */
        $posts = Post::find()
            ->where(['status' => Post::STATUS_PUBLISHED])
            ->orderBy(['published_on' => SORT_DESC])
            ->all();

        foreach ($posts as $postA) {
            $similar = $postA->indexSimilarPosts($posts);
            $postA->updateAttributes(['_similar' => $similar]);
        }
        $end = microtime(true);

        $time = round(($end - $start), 2);
        echo "Execution time: $time seconds\n";
    }

    /**
     * @param int $limit
     * @return array|self[]
     */
    public function getSimilarPosts($limit = 2)
    {
        $categories = $this->getOldAttribute('_tags');

        if ($categories && is_array($categories) && count($categories)) {
            return self::find()
                ->where([
                    '_tags' => ['$elemMatch' => ['$in' => $categories]],
                    'status' => self::STATUS_PUBLISHED,
                    '_id' => ['$ne' => $this->_id],
                ])
                ->orderBy(['published_on' => SORT_DESC])
                ->limit($limit)
                ->all();
        } else {
            return [];
        }
    }

    public function getInfoView($limit = 180)
    {
        return StringHelper::truncate($this->info, $limit);
    }

    const IMAGE_WIDTH = 720;

    protected function getDefaultMobileImage($width = 720, $height = null)
    {
        return self::getCropImage($this->image, $width, $height, ManipulatorInterface::THUMBNAIL_OUTBOUND, false);
    }

    public function isPushNotificationExpired()
    {
        $sendAnd = $this->getPushedOnTimeDiffAndroid();

        return $sendAnd == 0 || $sendAnd > 3600;
    }

    public function getPushedOnTimeDiffAndroid()
    {
        if ($this->pushed_on) {
            return time() - $this->pushed_on->getTimestamp();
        }

        return 0;
    }

    public function prepareMobilePost($force = false)
    {
        try {
            /** @var GalleryItem[] $gallery */
            $items = [];
            $this->mobile_image = trim($this->getDefaultMobileImage());
            $this->mobile_thumb = trim($this->getDefaultMobileImage(150, 150));
            $gallery = $this->getGalleryItemsModel();

            if (count($gallery) > 0) {
                foreach ($gallery as $item) {
                    if ($fullImage = $item->getImageCropped(720, null)) {
                        $items[] = [
                            'thumb' => trim($item->getImageCropped(320, 320)),
                            'image' => trim($fullImage),
                            'caption' => $item->caption,
                        ];
                    }
                }
                $this->gallery_items = $items;
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;
        }

    }

    public function getReadMinLabel()
    {
        return $this->read_min ? __('{min} Mins Read', ['min' => $this->read_min]) : '';
    }

    public function getImageWidth()
    {
        if (isset($this->image['width'])) {
            return $this->image['width'];
        }

        return null;
    }

    public function canDisplayImage()
    {
        return $this->hide_image == false && is_array($this->image);
    }

    const TEMPLATE_ONE = 1;
    const TEMPLATE_SIDEBAR = 0;

    protected $_cards = [];

    public function getCards()
    {
        $this->content = preg_replace_callback("'<h3 class=\"custom_card\">(.*?)</h3>'si", function ($data) {

            $title = strip_tags($data[1]);

            $this->_cards[] = $title;
            $id = count($this->_cards);

            return "<h3 class=\"custom_card\" id='card_step_$id' data-step='$id'><span>$id</span>{$title}</h3>";
        }, $this->content);

        return $this->_cards;
    }

    public function hasCards()
    {
        return preg_match("'<h3 class=\"custom_card\">(.*?)</h3>'si", $this->content, $match);;
    }

    public function getViewTemplate()
    {
        if ($this->hasCards()) {
            return 'card';
        }

        if ($this->template == self::TEMPLATE_ONE) {
            return 'view';
        }

        return 'view_sb';
    }

    public function getPublishedTimeIso()
    {
        return str_replace(":00", "00", date(DateTime::ATOM, $this->getPublishedOnSeconds()));
    }

    public function isLocked(Admin $user, $sessionId)
    {
        if ($this->_creator && $this->_creator != $user->_id) {
            return true;
        }

        return false;
    }

    public function releasePostLock(Admin $user = null)
    {
        return $this->updateAttributes([
            '_creator' => '',
            'creator_session' => '',
            'locked_on' => '',
        ]);
    }

    public function locForUser(Admin $user, $sessionId)
    {
        $this->_creator = $user->_id;
        $this->creator_session = $sessionId;
        $this->locked_on = call_user_func($this->getTimestampValue());

        return $this->updateAttributes([
            '_creator' => $this->_creator,
            'creator_session' => $this->creator_session,
            'locked_on' => $this->locked_on,
        ]);
    }


    /**
     * @param Admin $user
     * @return Post[]
     */
    public static function getLockedPosts(Admin $user)
    {
        return self::find()->where(['_creator' => $user->_id])->all();
    }

    public function checkImageFileExists()
    {
        if (is_array($this->image) && isset($this->image['path'])) {
            return self::checkFileExists($this->image['path']);
        }

        return false;
    }

    public function hasAuthor()
    {
        return $this->author instanceof Admin;
    }

    public function hasCategory()
    {
        return $this->category instanceof Category;
    }

    public function isColumnists()
    {
        return $this->_author || in_array(\common\models\Post::AUTHOR_CATEGORY, is_array($this->_categories) ? $this->_categories : []);
    }
}
