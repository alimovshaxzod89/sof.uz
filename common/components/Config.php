<?php

namespace common\components;

use common\models\Category;
use Yii;
use yii\base\Component;
use yii\mongodb\Query;
use yii\web\Request;

class Config extends Component
{
    const EVENT_CONFIG_INIT = 'eventConfigInit';
    const CONFIG_PUSH_TO_ANDROID = 'push_android';

    protected static $_configurations = [];
    protected static $_encryptKey     = 'u5ub5ub6u5uuX#*$rbv3GBbF3DuXF3D';

    public static function isLatinCyrill()
    {
        $langs = self::getLanguageOptions();
        return isset($langs[self::LANGUAGE_CYRILLIC]) && isset($langs[self::LANGUAGE_UZBEK]);
    }

    public static function getRootCatalog()
    {
        return self::get(self::CONFIG_CATALOG_POST);
    }

    public static function getDomain()
    {
        return Yii::$app->request instanceof Request ? Yii::$app->request->hostName : 'console';
    }


    public function init()
    {
        self::getSharedPaths();
        self::$_configurations = self::getConfigs();
        parent::init();
    }

    public static function collectionName()
    {
        return '_system_config';
    }

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


    const LANGUAGE_DEFAULT = 'cy-UZ';
    const LANGUAGE_UZBEK = 'uz-UZ';
    const LANGUAGE_CYRILLIC = 'cy-UZ';
    const LANGUAGE_RUSSIAN = 'ru-RU';

    const CONFIG_WEB_COMPRESS_ASSETS = 'web_compress_assets';

    const CONFIG_SYS_DEV_TOOLBAR_ENABLE = 'sys_dev_toolbar_enable';
    const CONFIG_SYS_DEV_TOOLBAR_IP = 'sys_dev_toolbar_ip';
    const CONFIG_SYS_DEV_EMAILS = 'sys_dev_emails';

    const CONFIG_USER_EMAIL_CONFIRM = 'user_email_confirm';

    const CONFIG_CATALOG_POST = 'post_category';
    const CONFIG_BLOCKED_IPS = 'blocked_ips';
    const CONFIG_ROLES = 'roles';

    const PASSWORD_FAKE_VALUE = '**************';

    const DEFAULT_LANG = 'cy-UZ';

    public static $languages = [
        self::LANGUAGE_UZBEK    => self::LANGUAGE_UZBEK,
        self::LANGUAGE_CYRILLIC => self::LANGUAGE_CYRILLIC,
        self::LANGUAGE_RUSSIAN  => self::LANGUAGE_RUSSIAN,
    ];

    public static function getLanguageOptions()
    {
        return [
            self::LANGUAGE_UZBEK    => __('Uzbek'),
            self::LANGUAGE_CYRILLIC => __('Cyrillic'),
            self::LANGUAGE_RUSSIAN  => __('Russian'),
        ];
    }

    public static function getLanguageOptionsWithShortLabel()
    {
        return [
            self::LANGUAGE_UZBEK    => __('O‘z'),
            self::LANGUAGE_CYRILLIC => __('Ўз'),
            self::LANGUAGE_RUSSIAN  => __('Ru'),
        ];
    }

    public static function getLanguagesTrans()
    {
        return [
            self::LANGUAGE_UZBEK    => null,
            self::LANGUAGE_CYRILLIC => null,
            self::LANGUAGE_RUSSIAN  => null,
        ];
    }

    public static function getHtmlLangSpec($lang)
    {
        $langs = [
            self::LANGUAGE_UZBEK    => 'uz-UZ',
            self::LANGUAGE_CYRILLIC => 'uz',
            self::LANGUAGE_RUSSIAN  => 'ru',
        ];

        return $langs[$lang];
    }

    public static function getShortLanguageOptions()
    {
        return [
            'uz' => __('Uzbek'),
            'cy' => __('Cyrillic'),
            'ru' => __('Russian'),
        ];
    }

    public static function getLanguageShortName()
    {
        $lang = [
            self::LANGUAGE_UZBEK    => 'uz',
            self::LANGUAGE_CYRILLIC => 'cy',
            self::LANGUAGE_RUSSIAN  => 'ru',
        ];
        return isset($lang[Yii::$app->language]) ? $lang[Yii::$app->language] : 'uz';
    }

    public static function getLanguage()
    {
        $lang = [
            self::LANGUAGE_UZBEK    => 'uz',
            self::LANGUAGE_CYRILLIC => 'cy',
            self::LANGUAGE_RUSSIAN  => 'ru',
        ];
        return isset($lang[Yii::$app->language]) ? Yii::$app->language : 'cy-UZ';
    }

    public static function getLanguageLabel($lang)
    {
        $labels = self::getLanguageOptions();

        return (isset($labels[$lang])) ? $labels[$lang] : $lang;
    }

    public static function get($path, $default = null)
    {
        if (array_key_exists($path, static::$_configurations)) return static::$_configurations[$path];

        return $default;
    }

    public static function set($path, $value)
    {
        /**
         * @var $mongo \yii\mongodb\Connection
         */

        $mongo = Yii::$app->mongodb;
        $mongo->getCollection(self::collectionName())->remove(['path' => $path]);
        if ($mongo->getCollection(self::collectionName())->insert(['path' => $path, 'value' => $value])) {
            self::$_configurations[$path] = $value;
        }
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
            ],

            __('Push Notifications') => [
                [
                    'label' => __('Enable Android'),
                    'path'  => self::CONFIG_PUSH_TO_ANDROID,
                    'type'  => 'boolean',
                    'help'  => __('Sends push notification to Android app'),
                ],
            ],
        );
    }

    public static $_sharedPaths;

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
}