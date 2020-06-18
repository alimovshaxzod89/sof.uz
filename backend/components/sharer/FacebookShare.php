<?php

namespace backend\components\sharer;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use yii\authclient\InvalidResponseException;

class FacebookShare extends BaseShare
{
    public $baseUrl = 'https://graph.facebook.com';
    public $pageId;

    public function publish($post)
    {
        try {
            // Returns a `FacebookFacebookResponse` object
            $fb = new Facebook([
                'app_id' => getenv('FB_APP_ID'),
                'app_secret' => getenv('FB_APP_SECRET'),
                'default_graph_version' => 'v2.10',
            ]);
            $link = $post->getShortViewUrl();
            $response = $fb->post(
                getenv('FB_PAGE_ID') . '/feed',
                [
                    'message' => $post->info . "\n\nБатафсил: $link"
                ],
                getenv('FB_ACCESS_TOKEN')
            );
        } catch (FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            return false;
        } catch (FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            return false;
        }
        $graphNode = $response->getGraphNode();

        return $graphNode;
    }
}