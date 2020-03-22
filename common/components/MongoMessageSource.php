<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace common\components;

use common\models\SystemMessage;
use Exception;
use Yii;
use yii\caching\Cache;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\i18n\MessageSource;
use yii\i18n\MissingTranslationEvent;
use yii\mongodb\Connection;

/**
 * MongoDbMessageSource extends [[MessageSource]] and represents a message source that stores translated
 * messages in MongoDB collection.
 * This message source uses single collection for the message translations storage, defined via [[collection]].
 * each entry in this collection should have 3 fields:
 * - language: string, translation language
 * - category: string, name translation category
 * - message: raw message
 * - translations: array, object of actual message translations, in each element: the key is language
 * value - message translation.
 * For example:
 * ```json
 * {
 *     "category": "app",
 *     "message": "Hello world!",
 *     "translations":
 *         {
 *             "uz-UZ": "Salom dunyo!",
 *             "de-DE": "Hallo Welt!",
 *             "ru-RU": "Привет мир!",
 *         }
 * }
 * ```
 * @since  2.0.5
 */
class MongoMessageSource extends MessageSource
{
    public $db = 'mongodb';

    /** @var Cache */
    public $cache = 'cache';

    public $collection = '_system_message';

    public $cachingDuration = 0;

    public $enableCaching = false;

    protected static $_collection;

    protected static $_cachingDuration = 0;

    protected static $messages = null;

    const GLOBAL_TRANSLATION = 'GLOBAL_TRANSLATION';

    public function init()
    {
        parent::init();

        self::$_collection = $this->collection;
        self::$_cachingDuration = $this->cachingDuration;
        $this->db = Instance::ensure($this->db, Connection::className());

        if ($this->enableCaching) {
            $this->cache = Instance::ensure($this->cache, Cache::className());
        }


    }

    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $event->translatedMessage = $event->message;
        $message = trim($event->message);

        if (empty($message)) return;


        if (!isset(self::$messages[$event->language])) {
            self::$messages[$event->language] = [];
        }

        /* @var $mongodb \yii\mongodb\Connection */
        $mongodb = Yii::$app->mongodb;

        self::$messages[$event->language] = self::loadCategoryMessagesFromDb($event->category, $event->language);

        if (!isset(self::$messages[$event->language][$message])) {
            $collection = $mongodb->getCollection('_system_message');

            try {
                $collection->insert(
                    ArrayHelper::merge([
                        'category' => $event->category,
                        'message' => $message,
                    ], Config::getLanguagesTrans())
                );

                $key = [
                    __CLASS__,
                    $event->category,
                    $event->language,
                ];

                Yii::$app->cache->delete($key);

                self::$messages[$event->language][$message] = $message;
            } catch (Exception $e) {
                Yii::error($e->getMessage());
            }
        }
    }


    protected function loadMessages($category, $language)
    {
        if ($this->enableCaching) {
            $key = [
                __CLASS__,
                $category,
                $language,
            ];
            $messages = $this->cache->get($key);
            if ($messages === false) {
                $messages = $this->loadMessagesFromDb($category, $language);
                $this->cache->set($key, $messages, $this->cachingDuration);
            }
            return $messages;
        } else {
            return $this->loadMessagesFromDb($category, $language);
        }
    }

    protected static function loadCategoryMessagesFromDb($category, $language)
    {
        $messages = [];

        $rows = SystemMessage::find()
            ->select(['message', $language])
            //->where(['category' => $category])
            ->asArray()
            ->all();

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                if (!isset($messages[trim($row['message'])]))
                    $messages[trim($row['message'])] = isset($row[$language]) && $row[$language] ? $row[$language] : trim($row['message']);
            }
        }

        return $messages;
    }

    protected function loadMessagesFromDb($category, $language)
    {
        $messages = [];

        $rows = SystemMessage::find()
            ->select(['message', $language])
            //->where(['category' => $category])
            ->asArray()
            ->all();

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                if (!isset($messages[trim($row['message'])]))
                    $messages[trim($row['message'])] = isset($row[$language]) && $row[$language] ? $row[$language] : trim($row['message']);
            }
        }

        return $messages;
    }
}