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


use yii\authclient\InvalidResponseException;

class FacebookShare extends BaseShare
{
    public $baseUrl = 'https://graph.facebook.com';
    public $pageId;

    public function publish($post)
    {
        $response = $this->createApiRequest()
                         ->setMethod('POST')
                         ->setUrl($this->pageId . '/feed')
                         ->setData(['access_token' => $this->accessToken, 'message' => $post->info])
                         ->send();

        if (!$response->getIsOk()) {
            throw new InvalidResponseException($response, 'Request failed with code: ' . $response->getStatusCode() . ', message: ' . $response->getContent());
        }
        return $response->getData();
    }
}