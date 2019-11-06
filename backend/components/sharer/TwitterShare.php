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

        $static = \Yii::getAlias('@static/uploads');

        /** @var TwitterOAuth $request */
        $connection = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);
        $connection->setTimeouts(10, 15);
        $photo = $post->getFilePath('image', true);

        if ($photo)
            $media = $connection->upload('media/upload', ['media' => $photo]);

        $parameters = [
            'status'    => $post->getTranslation('title', Config::LANGUAGE_CYRILLIC) . "\n" . $post->getShortViewUrl(),
            'media_ids' => $photo ? $media->media_id_string : ''
        ];
        $result     = $connection->post('statuses/update', $parameters);

        return $connection->getLastHttpCode() == 200;
    }
}