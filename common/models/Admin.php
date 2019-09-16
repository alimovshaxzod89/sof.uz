<?php

namespace common\models;

use common\components\Config;
use common\components\Translator;
use Imagine\Image\ManipulatorInterface;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\base\Exception;
use yii\bootstrap\Html;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 * @property string $full_name
 * @property string $slug
 * @property string $login
 * @property string $password
 * @property string $image
 * @property string $description
 * @property string $email
 * @property string $telephone
 * @property string $status
 * @property string $auth_key
 * @property string $access_token
 * @property string $access_token_date
 * @property string $password_reset_token
 * @property string $password_reset_date
 * @property string $resource
 * @property string $language
 *
 * @property Post   $postOne
 */
class Admin extends MongoModel implements IdentityInterface
{
    protected $_searchableTextAttributes = ['full_name', 'login', 'email'];
    const SCENARIO_PROFILE = 'profile';

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'full_name',
            'slug',
            'login',
            'password',
            'image',
            'description',
            'email',
            'telephone',
            'status',
            'auth_key',
            'access_token',
            'access_token_date',
            'password_reset_token',
            'password_reset_date',
            'resource',
            'language',
        ]);
    }

    public $confirmation;
    public $change_password;
    public $isSuperAdmin = false;

    const STATUS_ENABLE = 'enable';
    const STATUS_DISABLE = 'disable';
    const STATUS_BLOCKED = 'blocked';

    const SUPER_ADMIN_LOGIN = 'admin';

    const CACHE_KEY_ADMIN_MENU = 'admin_menu';
    const CACHE_TAG_ADMIN_MENU = 'admin_menu';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_ENABLE  => __('Enabled'),
            self::STATUS_DISABLE => __('Disabled'),
            self::STATUS_BLOCKED => __('Blocked'),
        ];
    }


    public static function getArrayOptions()
    {
        $data = self::find()
                    ->orderBy(['full_name' => SORT_ASC])
                    ->where(['status' => self::STATUS_ENABLE])
                    ->all();

        return ArrayHelper::merge([], ArrayHelper::map($data, 'id', 'full_name'));
    }

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name', 'login', 'email', 'slug', 'status'], 'required', 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE]],

            [['full_name', 'email', 'language', 'telephone'], 'required', 'on' => self::SCENARIO_PROFILE],

            [['password', 'confirmation'], 'required', 'on' => self::SCENARIO_INSERT],

            [['password', 'confirmation'], 'required', 'on' => [self::SCENARIO_UPDATE, self::SCENARIO_PROFILE], 'when' => function ($model) {
                return $model->change_password == 1;
            }, 'whenClient'                                 => "function (attribute, value) {return $('#change_password').is(':checked');}"],

            [['confirmation'], 'compare', 'on' => self::SCENARIO_INSERT, 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => __('Confirmation does not match')],

            [['confirmation'], 'compare', 'on' => [self::SCENARIO_INSERT, self::SCENARIO_PROFILE], 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => __('Confirmation does not match'), 'when' => function ($model) {
                return $model->change_password == 1;
            }],

            [['language'], 'in', 'range' => Config::getLanguageLocales()],
            [['resource'], 'safe', 'on' => self::SCENARIO_UPDATE],

            [['login', 'email'], 'unique', 'on' => [self::SCENARIO_INSERT, self::SCENARIO_UPDATE]],
            [['email'], 'email'],

            [['slug', 'image', 'description', 'change_password'], 'safe'],

            [['full_name', 'password'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 64],

            [['telephone'], 'string', 'max' => 32],
            [['password_reset_token'], 'string', 'max' => 255],


            [['search'], 'safe', 'on' => self::SCENARIO_SEARCH],
        ];
    }

    public function getPostOne()
    {
        return $this->hasOne(Post::class, ['_author' => '_id']);
    }

    /**
     * Finds user by login
     * @param string $login
     * @return Admin|null
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login, 'status' => self::STATUS_ENABLE]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function setPassword($password)
    {
        $this->password          = Yii::$app->security->generatePasswordHash($password);
        $this->auth_key          = Yii::$app->security->generateRandomString();
        $this->access_token      = Yii::$app->security->generateRandomString();
        $this->access_token_date = call_user_func($this->getTimestampValue());

        $this->removePasswordResetToken();

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
        $this->isSuperAdmin = $this->login == self::SUPER_ADMIN_LOGIN;

        return parent::afterFind();
    }

    public function beforeDelete()
    {
        if ($this->isSuperAdmin) {
            throw new Exception(__('Can not delete supper admin'));
        }

        TagDependency::invalidate(Yii::$app->cache, [self::CACHE_TAG_ADMIN_MENU]);

        return parent::beforeDelete();
    }

    public function beforeSave($insert)
    {
        if ($this->change_password || $this->isNewRecord) $this->setPassword($this->confirmation);
        if ($this->isSuperAdmin) {
            $this->status = self::STATUS_ENABLE;
            $this->login  = self::SUPER_ADMIN_LOGIN;
            $this->email  = 'admin@activemedia.uz';
        }

        if (empty($this->slug) || $this->isNewRecord) {
            $slug       = Translator::getInstance()->translateToLatin($this->full_name);
            $this->slug = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($slug)), '-');
        }

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

        TagDependency::invalidate(Yii::$app->cache, [self::CACHE_TAG_ADMIN_MENU]);

        return parent::beforeSave($insert);
    }

    public function canAccessToResource($path)
    {
        $path = trim($path, '/');
        return $this->isSuperAdmin || isset($this->resource[$path]) || is_array($this->resource) && in_array($path, $this->resource);
    }

    /**
     * @param $token
     * @return Admin
     */
    public static function findByPasswordResetToken($token)
    {
        $admin = static::findOne([
                                     'password_reset_token' => $token,
                                     'status'               => self::STATUS_ENABLE,
                                 ]);

        return $admin && $admin->isPasswordResetTokenValid() ? $admin : null;
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString();
        $this->password_reset_date  = call_user_func($this->getTimestampValue());
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

        if ($this->password_reset_date instanceof Timestamp && $this->password_reset_token) {
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

    public function getCroppedImage($width = 870, $height = 260, $manipulation = 2, $watermark = false)
    {
        $manipulation = $manipulation == 1 ? ManipulatorInterface::THUMBNAIL_OUTBOUND : ManipulatorInterface::THUMBNAIL_INSET;
        return parent::getCropImage($this->image, $width, $height, $manipulation, $watermark);
    }

    public function getImageUrl($width = 120, $height = 120, $manipulation = 1)
    {
        $manipulation = $manipulation == 1 ? ManipulatorInterface::THUMBNAIL_OUTBOUND : ManipulatorInterface::THUMBNAIL_INSET;
        return self::getCropImage($this->image, $width, $height, $manipulation);
    }

    public function getViewUrl($scheme = false)
    {
        /* @var $urlManager \codemix\localeurls\UrlManager */
        return Yii::$app->viewUrl
            ->createAbsoluteUrl(['category/author', 'slug' => $this->slug], $scheme);
    }
}
