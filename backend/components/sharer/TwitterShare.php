<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 11/27/17
 * Time: 9:39 PM
 */

namespace backend\components\sharer;


use Abraham\TwitterOAuth\TwitterOAuth;

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

        $response = $request->post('statuses/update', ['status' => $post->info]);
        \Yii::trace(var_dump($response));
        return $request->getLastHttpCode() == 200;
    }
}