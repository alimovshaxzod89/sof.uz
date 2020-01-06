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
    /**
     * @var BotApi
     */
    protected $_botApi;

    public function init()
    {
        if (empty($this->apiKey)) {
            throw new Exception('Firebase API Key cannot be empty');
        }
        if (empty($this->topics)) {
            throw new Exception('Topics cannot be empty');
        }
    }

    public function publish($post, $force = false)
    {
        $result = [];
        $post   = Post::findOne($post->id);

        if ($post->getPushedOnTimeDiffAndroid() == 0 || $force) {
            $client = new Client(['base_uri' => 'https://fcm.googleapis.com/']);

            $oldLang = \Yii::$app->language;

            foreach (!empty($this->condition) ? $this->condition : $this->to as $lang => $topic) {

                \Yii::$app->language = $lang;
                $post->refresh();

                $params = [
                    'json'    => [
                        'priority' => 'normal',
                        'data'     => $post->toArray(),
                    ],
                    'headers' => [
                        'Authorization' => $this->apiKey,
                    ],
                ];

                if (!empty($this->condition)) {
                    $params['json']['condition'] = $topic;
                } else {
                    $params['json']['to'] = $topic;
                }

                try {
                    $result = $client->post('fcm/send', $params);
                    $result = $result->getBody()->getContents();

                    if ($data = Json::decode($result, true)) {
                        if (isset($data['message_id'])) {
                            $post->updateAttributes(['pushed_on' => call_user_func($post->getTimestampValue())]);
                        }
                        $result[] = $data;
                    }
                } catch (Exception $e) {

                }
            }

            \Yii::$app->language = $oldLang;
        }


        return $result;
    }
}