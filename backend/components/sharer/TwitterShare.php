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
        /** @var TwitterOAuth $request */
        $request = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->accessTokenSecret);

        $text     = $post->getTranslation('title', Config::LANGUAGE_CYRILLIC) . "\n\n" . $post->getShortViewUrl();
        $response = $request->post('statuses/update', ['status' => $text]);
        \Yii::trace(var_dump($response));
        return $request->getLastHttpCode() == 200;
    }
}