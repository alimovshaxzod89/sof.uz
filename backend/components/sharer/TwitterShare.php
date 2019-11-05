<?php

namespace backend\components\sharer;


use Abraham\TwitterOAuth\TwitterOAuth;
use common\components\Config;

class TwitterShare extends BaseShare
{
    public $baseUrl = 'https://api.twitter.com/1.1';
    /**
     * @var  string
     */
    public $accessTokenSecret;
    public $consumerKey;
    public $consumerSecret;

    public function applyTokenToRequest($request)
    {
    }

    public function prepareRequest($request)
    {

    }

    public function publish($post)
    {

        $static = \Yii::getAlias('@staticUrl/uploads');

        /** @var TwitterOAuth $request */
        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);
        $connection->setTimeouts(10, 15);

        $media = $connection->upload('media/upload', ['media' => "{$static}/{$post->image['path']}"]);
        $parameters = [
            'status' => $post->getTranslation('title', Config::LANGUAGE_CYRILLIC) . "\n\n" . $post->getShortViewUrl(),
            'media_ids' => $media->media_id_string
        ];
        $result = $connection->post('statuses/update', $parameters);

        return $connection->getLastHttpCode() == 200;
    }
}