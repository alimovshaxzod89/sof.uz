<?php

namespace common\models;

use common\components\Config;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * Class Blogger
 * @property string    $full_name
 * @property string    $slug
 * @property string    $facebook
 * @property string    $telegram
 * @property string    $twitter
 * @property string    $password
 * @property string    $email
 * @property string    $phone
 * @property string    $intro
 * @property string    $redaction
 * @property string    $job
 * @property string    $auth_key
 * @property string    $access_token
 * @property string    $access_token_date
 * @property string    $password_reset_token
 * @property Timestamp $password_reset_date
 * @property string    $resource
 * @property string    $language
 * @property string    $status
 * @property string    $posts_l5d
 * @property string    $views_l3d
 * @property array     image
 * @property Post[]    articles
 * @property mixed     description
 */
class Blogger extends MongoModel implements IdentityInterface
{
    protected $_translatedAttributes = ['full_name', 'description', 'intro', 'job'];
    protected $_booleanAttributes    = ['redaction'];
    protected $_integerAttributes    = ['position'];

    public static function getArrayOptions()
    {
        $data = self::find()
                    ->orderBy(['full_name' => SORT_ASC])
                    ->where(['status' => self::STATUS_ENABLE])
                    ->all();

        return array_merge([], ArrayHelper::map($data, 'id', 'full_name'));
    }

    /**
     * @return Blogger[]
     */
    public static function getRedaction()
    {
        return self::find()
                   ->orderBy(['position' => SORT_ASC])
                   ->where(['status' => self::STATUS_ENABLE, 'redaction' => true])
                   ->all();
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'full_name',
            'description',
            'intro',
            'facebook',
            'telegram',
            'twitter',
            'job',
            'login',
            'email',
            'phone',
            'slug',
            'position',
            'redaction',
            'image',
            'auth_key',
            'access_token',
            'access_token_date',
            'resource',
            'language',
            'status',
            'posts',
            'posts_l5d',
            'views_l3d',
        ]);
    }


    public $search;
    public $confirmation;
    public $change_password;

    const STATUS_ENABLE = 'enable';
    const STATUS_DISABLE = 'disable';

    const CACHE_KEY_BLOGGER_MENU = 'blogger_menu';
    const CACHE_TAG_BLOGGER_MENU = 'blogger_menu';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_ENABLE  => __('Enabled'),
            self::STATUS_DISABLE => __('Disabled'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'blogger';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['facebook', 'telegram', 'twitter', 'description', 'intro', 'position', 'job', 'redaction'], 'safe'],
            [['full_name', 'status', 'image', 'slug'], 'required', 'on' => ['insert', 'update']],

            [['full_name', 'language', 'image', 'description', 'intro'], 'required', 'on' => ['profile']],

            [['language'], 'in', 'range' => array_keys(Config::getLanguageOptions())],
            [['resource'], 'safe', 'on' => ['update']],

            [['email', 'facebook', 'telegram', 'twitter'], 'unique', 'on' => ['update', 'insert']],
            [['email'], 'email'],

            [['created_at', 'updated_at'], 'safe'],
            [['position'], 'number'],

            [['full_name'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 64],
            [['intro'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 1024],


            [['search'], 'safe', 'on' => 'search'],
        ];
    }

    /**
     * Finds user by login
     * @param string $login
     * @return Blogger|null
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ENABLE]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['_id' => $id, 'status' => self::STATUS_ENABLE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ENABLE]);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function afterFind()
    {
        return parent::afterFind();
    }

    public function beforeDelete()
    {
        TagDependency::invalidate(Yii::$app->cache, [self::CACHE_TAG_BLOGGER_MENU]);

        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {

        if ($this->isNewRecord) {
            $this->resource = [];
        }

        if ($this->isAttributeChanged('resource')) {
            $resources = [];
            foreach ($this->resource as $resource) {
                $resource             = trim($resource, ' /');
                $resources[$resource] = $resource;
                foreach (explode(',', $resource) as &$item) {
                    $resources[$item] = $item;
                }
            }
            $this->resource = array_values($resources);
        }

        return parent::beforeSave($insert);
    }

    public function canAccessToResource($path)
    {
        $path = trim($path, '/');
        return isset($this->resource[$path]) || is_array($this->resource) && in_array($path, $this->resource);
    }


    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => 20,
                                                   ],
                                               ]);

        $this->load($params);

        if ($this->search) {
            $query->orFilterWhere(['full_name' => ['$regex' => $this->search, '$options' => 'si']]);
            $query->orFilterWhere(['login' => ['$regex' => $this->search, '$options' => 'si']]);
            $query->orFilterWhere(['email' => ['$regex' => $this->search, '$options' => 'si']]);

            foreach (Config::getLanguageCodes() as $code) {
                $query->orFilterWhere(['_translations.full_name_' . $code => ['$regex' => $this->search, '$options' => 'si']]);
            }
        }

        return $dataProvider;
    }

    /**
     * @param $token
     * @return Blogger
     */
    public static function findByPasswordResetToken($token)
    {
        $blogger = static::findOne([
                                       'password_reset_token' => $token,
                                       'status'               => self::STATUS_ENABLE,
                                   ]);

        return $blogger && $blogger->isPasswordResetTokenValid() ? $blogger : null;
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString();
        $this->password_reset_date  = $this->getTimestampValue();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
        $this->password_reset_date  = null;
    }

    public function isPasswordResetTokenValid()
    {
        if ($this->password_reset_date && $this->password_reset_token) {
            $expire = Yii::$app->params['admin.passwordResetTokenExpire'];

            return $this->password_reset_date->sec + $expire >= time();
        }

        return false;
    }


    public function getFullName()
    {
        return Html::encode($this->full_name ?: $this->login);
    }


    public function login()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
        $this->save();
        return $this;
    }

    public function getArticles()
    {
        return $this->hasMany(Post::class, ['_creator' => 'id']);
    }


    public static function indexPostsCount()
    {
        /**
         * @var $post   Post
         * @var $tag    Tag
         * @var $author Blogger
         */
        $authors = self::findAll(['status' => self::STATUS_ENABLE]);

        foreach ($authors as $author) {
            $postsL5d = Post::find()
                            ->andWhere(['status' => Post::STATUS_PUBLISHED])
                            ->andWhere(['_creator' => $author->getId()])
                            ->andWhere([
                                           'published_on' => ['$gt' => new Timestamp(1, time() - 5 * 24 * 3600)],
                                       ])
                            ->count();

            $postsAll = Post::find()
                            ->select(['_id', 'views_l3d'])
                            ->andWhere(['status' => Post::STATUS_PUBLISHED])
                            ->andWhere(['_creator' => $author->getId()])
                            ->all();

            $views = 0;
            foreach ($postsAll as $post) {
                $views += $post->views_l3d;
            }

            $author->updateAttributes(['posts' => count($postsAll), 'posts_l5d' => $postsL5d, 'views_l3d' => $views]);
        }
    }


    public function getViewUrl($scheme = true)
    {
        return Url::to(['category/author', 'slug' => $this->slug], $scheme);
    }

    public function getImageUrl($width = 120, $height = 120)
    {
        return self::getCropImage($this->image, $width, $height);
    }
}
