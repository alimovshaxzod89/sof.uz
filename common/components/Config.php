<?php

namespace common\components;

use common\models\Category;
use Yii;
use yii\base\Component;
use yii\data\ArrayDataProvider;
use yii\mongodb\Query;
use yii\web\Request;

class Config extends Component
{
    const EVENT_CONFIG_INIT = 'eventConfigInit';
    const CONFIG_PUSH_TO_ANDROID = 'push_android';
    const LANGUAGE_DEFAULT = self::LANGUAGE_CYRILLIC;

    const LANGUAGE_UZBEK = 'uz-UZ';
    const LANGUAGE_CYRILLIC = 'oz-UZ';
    const LANGUAGE_RUSSIAN = 'ru-RU';
    const LANGUAGE_ENGLISH = 'en-US';
    const LANGUAGE_UZBEK_CODE = 'uz';
    const LANGUAGE_CYRILLIC_CODE = 'oz';
    const LANGUAGE_RUSSIAN_CODE = 'ru';
    const LANGUAGE_ENGLISH_CODE = 'en';

    const CONFIG_WEB_COMPRESS_ASSETS = 'web_compress_assets';
    const CONFIG_SYS_DEV_TOOLBAR_ENABLE = 'sys_dev_toolbar_enable';
    const CONFIG_SYS_DEV_TOOLBAR_IP = 'sys_dev_toolbar_ip';
    const CONFIG_SYS_DEV_EMAILS = 'sys_dev_emails';
    const CONFIG_USER_EMAIL_CONFIRM = 'user_email_confirm';
    const CONFIG_CATALOG_POST = 'post_category';
    const CONFIG_FOOTER_TOP_POSTS = 'footer_top_posts';
    const CONFIG_BLOCKED_IPS = 'blocked_ips';

    const CONFIG_ROLES = 'roles';
    const PASSWORD_FAKE_VALUE = '**************';
    public static    $_sharedPaths;
    protected static $_configurations = [];
    protected static $_encryptKey     = 'u5ub5ub6u5uuX#*$rbv3GBbF3DuXF3D';

    public static function isLatinCyrill()
    {
        $languages = self::getLanguageOptions();
        return isset($languages[self::LANGUAGE_CYRILLIC]) && isset($languages[self::LANGUAGE_UZBEK]);
    }

    public static function getAllLanguagesWithLabels()
    {
        return [
            self::LANGUAGE_UZBEK    => __("O'zbekcha"),
            self::LANGUAGE_CYRILLIC => __("Ўзбекча"),
            self::LANGUAGE_RUSSIAN  => __('Russian'),
        ];
    }

    public static function getLanguageOptions()
    {
        $out        = [];
        $languages  = self::getAllLanguagesWithLabels();
        $activeList = self::getLanguagesTrans();
        foreach ($languages as $locale => $label) {
            if (array_key_exists($locale, $activeList)) {
                $out[$locale] = $label;
            }
        }

        return $out;
    }

    public static function getRootCatalog()
    {
        return self::get(self::CONFIG_CATALOG_POST);
    }

    public static function get($path, $default = null)
    {
        if (array_key_exists($path, static::$_configurations)) return static::$_configurations[$path];

        return $default;
    }

    public static function getDomain()
    {
        return Yii::$app->request instanceof Request ? Yii::$app->request->hostName : 'console';
    }

    public static function getLanguageOptionsWithShortLabel()
    {
        return [
            self::LANGUAGE_UZBEK    => __('O‘z'),
            self::LANGUAGE_CYRILLIC => __('Ўз'),
            self::LANGUAGE_RUSSIAN  => __('Ru'),
        ];
    }

    public static function getLanguages()
    {
        $languages = [];
        if (Yii::$app->urlManager instanceof \codemix\localeurls\UrlManager)
            $languages = Yii::$app->urlManager->languages;

        return $languages;
    }

    public static function getLanguageCodes()
    {
        return array_keys(self::getLanguages());
    }

    public static function getLanguageLocales()
    {
        return array_values(self::getLanguages());
    }

    public static function getLanguagesTrans()
    {
        $languages = array_flip(self::getLanguageLocales());
        return array_map(function ($v) {
            return null;
        }, $languages);
    }

    public static function getHtmlLangSpec($language)
    {
        $language  = $language ?: Yii::$app->language;
        $languages = [
            self::LANGUAGE_UZBEK    => 'uz-UZ',
            self::LANGUAGE_CYRILLIC => 'uz',
            self::LANGUAGE_RUSSIAN  => 'ru',
        ];
        return $languages[$language];
    }

    public static function getLanguageCode($locale = false)
    {
        $locale    = $locale ?: Yii::$app->language;
        $languages = array_flip(self::getLanguages());
        return isset($languages[$locale]) ? $languages[$locale] : self::getLanguageCode(self::LANGUAGE_DEFAULT);
    }

    public static function getLanguageLabel($locale = false)
    {
        $languagesWithLocale = self::getLanguageOptions();
        $locale              = $locale ?: Yii::$app->language;
        return isset($languagesWithLocale[$locale]) ? $languagesWithLocale[$locale] : self::getLanguageLabel(Config::LANGUAGE_DEFAULT);
    }

    public static function getAsArray($path, $default = null)
    {
        if (array_key_exists($path, static::$_configurations)) {
            $values = explode("\n", static::$_configurations[$path]);
            array_walk($values, function (&$item, $index) {
                $item = trim($item);
            });
            return array_filter($values);
        }

        return $default;
    }

    public static function getEncrypted($path, $default = null)
    {
        if (array_key_exists($path, static::$_configurations)) return Yii::$app->security->decryptByKey(static::$_configurations[$path], self::$_encryptKey);

        return $default;
    }

    public static function processValue($path, $value)
    {
        if (self::$_sharedPaths[$path] === 'password') {
            if ($value == self::PASSWORD_FAKE_VALUE) {
                return self::get($path);
            }
            return Yii::$app->getSecurity()->encryptByKey($value, self::$_encryptKey);
        }
        return $value;
    }

    public static function batchUpdate($configuration = [])
    {
        if (count($configuration))
            foreach ($configuration as $path => $value) {
                Config::set($path, $value);
            }
        self::afterConfigChange();
        return true;
    }

    public static function set($path, $value)
    {
        /* @var $mongo \yii\mongodb\Connection */
        $mongo      = Yii::$app->mongodb;
        $collection = $mongo->getCollection(self::collectionName());

        $collection->remove(['path' => $path]);
        if ($collection->insert(['path' => $path, 'value' => $value])) {
            self::$_configurations[$path] = $value;
        }
    }

    public static function collectionName()
    {
        return '_system_config';
    }

    public static function afterConfigChange()
    {
        self::$_configurations = self::getConfigs();

        $debug = '';
        $ips   = '';
        if (self::get(self::CONFIG_SYS_DEV_TOOLBAR_ENABLE)) {
            $debug = "'debug'";
            $ips   = explode(',', self::get(self::CONFIG_SYS_DEV_TOOLBAR_IP));
            foreach ($ips as $i => $ip) {
                $ip      = trim($ip);
                $ips[$i] = "'$ip'";
            }
            $ips = implode(', ', $ips);
        }

        $config = "<?php
return [
    'bootstrap' => [$debug],
    'modules'   => [
        'debug' => [
            'class'           => 'yii\\debug\\Module',
            'enableDebugLogs' => false,
            'allowedIPs'      => [$ips],
            'panels' => [
                'mongodb' => [
                    'class' => 'yii\\mongodb\\debug\\MongoDbPanel',
                ],
                'queue'   => [
                    'class' => 'yii\\queue\\debug\\Panel',
                ],
                'httpclient' => [
                    'class' => 'yii\\httpclient\\debug\\HttpClientPanel',
                ],
            ],
        ],
    ]
];
        ";


        file_put_contents(Yii::getAlias('@common' . DS . 'config' . DS . 'main-local.php'), $config);
    }

    public static function getDateRangeLocale()
    {
        return [
            "format"           => "d-m-Y H:i",
            "separator"        => " / ",
            "applyLabel"       => __("Apply"),
            "cancelLabel"      => __("Cancel"),
            "fromLabel"        => __("From"),
            "toLabel"          => __("To"),
            "customRangeLabel" => __("Custom"),
            "weekLabel"        => "W",
            "daysOfWeek"       => [
                __("Su"),
                __("Mo"),
                __("Tu"),
                __("We"),
                __("Th"),
                __("Fr"),
                __("Sa"),
            ],
            "monthNames"       => [
                __("January"),
                __("February"),
                __("March"),
                __("April"),
                __("May"),
                __("June"),
                __("July"),
                __("August"),
                __("September"),
                __("October"),
                __("November"),
                __("December"),
            ],
            "firstDay"         => 1,
        ];
    }

    public static function getActiveLanguageUrlArray($code = false)
    {
        $url = array_merge([Yii::$app->controller->route], Yii::$app->request->get());
        if ($code)
            $url = array_merge($url, ['language' => $code]);

        return $url;
    }

    /**
     * @param int $limit
     * @return ArrayDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getBackupProvider($limit = 20)
    {
        $dir  = Yii::getAlias('@backups') . DS;
        $data = [];
        foreach (glob($dir . '*.bak.*') as $file) {
            $data[] = [
                'name' => basename($file),
                'size' => Yii::$app->formatter->asSize(filesize($file)),
                'time' => Yii::$app->formatter->asDatetime(filemtime($file)),
                'date' => intval(filemtime($file)),
            ];
        }

        return new ArrayDataProvider([
                                         'allModels'  => $data,
                                         'sort'       => [
                                             'defaultOrder' => [
                                                 'date' => SORT_DESC
                                             ],
                                             'attributes'   => [
                                                 'name',
                                                 'size',
                                                 'time',
                                                 'date'
                                             ],
                                         ],
                                         'pagination' => [
                                             'pageSize' => $limit,
                                         ],
                                     ]);
    }

    public function init()
    {
        self::getSharedPaths();
        self::$_configurations = self::getConfigs();
        parent::init();
    }

    public static function getSharedPaths()
    {
        if (!self::$_sharedPaths) {
            $paths  = self::getAllConfiguration();
            $result = [];
            foreach ($paths as $items) {
                foreach ($items as $item) {
                    $result[$item['path']] = $item['type'];
                }
            }

            self::$_sharedPaths = $result;
        }
        return self::$_sharedPaths;
    }

    public static function getAllConfiguration()
    {
        $rootCats = Category::getRootCategoriesAsOption();

        return array(
            __('System Developer') => [
                [
                    'label' => __('Enable Developer Toolbar'),
                    'path'  => self::CONFIG_SYS_DEV_TOOLBAR_ENABLE,
                    'type'  => 'boolean',
                    'help'  => __('Shows developer toolbar to debug YII application'),
                ],
                [
                    'label' => __('Developer IP'),
                    'path'  => self::CONFIG_SYS_DEV_TOOLBAR_IP,
                    'type'  => 'text',
                    'help'  => __('Enter comma separated IP addresses'),
                ],
                [
                    'label' => __('Blocked IPs'),
                    'path'  => self::CONFIG_BLOCKED_IPS,
                    'type'  => 'text',
                    'help'  => __('Enter comma separated IP addresses'),
                ],
                [
                    'label' => __('Email Alerts'),
                    'path'  => self::CONFIG_SYS_DEV_EMAILS,
                    'type'  => 'textarea',
                    'help'  => __('Enter each email on new line'),
                ],
            ],

            __('Catalog') => [
                [
                    'label'   => __('Post Catalog'),
                    'path'    => self::CONFIG_CATALOG_POST,
                    'type'    => 'category',
                    'help'    => __('Select root category for posts'),
                    'options' => $rootCats,
                ],
                [
                    'label'   => __('Menu Category'),
                    'path'    => self::CONFIG_FOOTER_TOP_POSTS,
                    'type'    => 'category',
                    'help'    => __('Select category'),
                    'options' => $rootCats,
                ],
            ],

            __('Social') => [
                [
                    'label' => __('Facebook link'),
                    'path'  => self::CONFIG_SOCIAL_FACEBOOK_LINK,
                    'type'  => 'text',
                    'help'  => __('link social'),
                ],
                [
                    'label' => __('Youtube link'),
                    'path'  => self::CONFIG_SOCIAL_YOUTUBE_LINK,
                    'type'  => 'text',
                    'help'  => __('link social'),
                ],
                [
                    'label' => __('Twitter link'),
                    'path'  => self::CONFIG_SOCIAL_TWITTER_LINK,
                    'type'  => 'text',
                    'help'  => __('link social'),
                ],
                /*[
                    'label' => __('Instagram link'),
                    'path'  => self::CONFIG_SOCIAL_INSTAGRAM_LINK,
                    'type'  => 'text',
                    'help'  => __('link social'),
                ],*/
                [
                    'label' => __('Telegram link'),
                    'path'  => self::CONFIG_SOCIAL_TELEGRAM_LINK,
                    'type'  => 'text',
                    'help'  => __('link social'),
                ],
            ],
        );
    }

    const CONFIG_SOCIAL_FACEBOOK_LINK = 'social_facebook_link';
    const CONFIG_SOCIAL_YOUTUBE_LINK = 'social_youtube_link';
    const CONFIG_SOCIAL_TWITTER_LINK = 'social_twitter_link';
    //const CONFIG_SOCIAL_INSTAGRAM_LINK = 'social_instagram_link';
    const CONFIG_SOCIAL_TELEGRAM_LINK = 'social_telegram_link';

    public static function getConfigs()
    {
        $rows = (new Query())
            ->select(['path', 'value'])
            ->from('_system_config')
            ->all(Yii::$app->mongodb);

        $result = [];

        array_walk($rows, function ($item) use (&$result) {
            $result[$item['path']] = $item['value'];
        });

        return $result;
    }
}