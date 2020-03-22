<?php

namespace backend\components\sharer;

use api\models\v1\Post;
use common\components\Config;
use Exception;
use GuzzleHttp\Client;
use TelegramBot\Api\BotApi;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;

class Firebase extends BaseShare
{
    /**
     * @var string
     */
    public $apiKey;
    public $topics;
    public $condition;
    public $to;
    public $toIos;
    /**
     * @var BotApi
     */
    protected $_botApi;

    public function init()
    {
        if (empty($this->apiKey)) {
            throw new Exception('Firebase API Key cannot be empty');
        }
    }

    public function publish($post, $force = false)
    {
        $results = [
            'params' => [],
            'errors' => [],
            'messages' => [],
        ];
        $post = Post::findOne($post->id);

        if ($post->getPushedOnTimeDiffAndroid() == 0 || $force) {
            $client = new Client(['base_uri' => 'https://fcm.googleapis.com/']);

            $oldLang = \Yii::$app->language;

            foreach ($this->to as $lang => $topic) {

                \Yii::$app->language = $lang;
                $post->refresh();

                $params = [
                    'json' => [
                        'priority' => 'normal',
                        'data' => $post->toArray(),
                    ],
                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ],
                ];

                $params['json']['to'] = $topic;
                $results['params'] = $params;

                try {
                    $result = $client->post('fcm/send', $params);
                    $result = $result->getBody()->getContents();

                    if ($data = Json::decode($result, true)) {
                        if (isset($data['message_id'])) {
                            $post->updateAttributes(['pushed_on' => call_user_func($post->getTimestampValue())]);
                        }
                        $results['messages'][] = $data;
                    }
                } catch (Exception $e) {
                    $results['errors'][] = $e->getMessage();
                }
            }

            \Yii::$app->language = $oldLang;
        }


        return $results;
    }

    public function publishIos($post, $force = false)
    {
        $results = [
            'params' => [],
            'errors' => [],
            'messages' => [],
        ];
        $post = Post::findOne($post->id);

        if ($post->getPushedOnTimeDiffAndroid() == 0 || $force) {
            $client = new Client(['base_uri' => 'https://fcm.googleapis.com/']);

            $oldLang = \Yii::$app->language;

            foreach ($this->toIos as $lang => $topic) {

                \Yii::$app->language = $lang;
                $post->refresh();

                $params = [
                    'json' => [
                        'priority' => 'normal',
                        'data' => $post->toArray(),
                        'notification' => [
                            'title' => $post->title,
                            'body' => $post->info,
                        ]
                    ],
                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ],
                ];

                $params['json']['to'] = $topic;
                $results['params'][] = $params;

                try {
                    $result = $client->post('fcm/send', $params);
                    $result = $result->getBody()->getContents();

                    if ($data = Json::decode($result, true)) {
                        if (isset($data['message_id'])) {
                            $post->updateAttributes(['pushed_on' => call_user_func($post->getTimestampValue())]);
                        }
                        $results['messages'][] = $data;
                    }
                } catch (Exception $e) {
                    $results['errors'][] = $e->getMessage();
                }
            }

            \Yii::$app->language = $oldLang;
        }


        return $results;
    }
}