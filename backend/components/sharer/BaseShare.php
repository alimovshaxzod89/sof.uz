<?php

namespace backend\components\sharer;


use common\models\Post;
use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\Response;

abstract class BaseShare extends Component
{
    /** @var  Client object */
    private $_httpClient;

    public $baseUrl;
    public $accessToken;

    protected function setHttpClient($client)
    {
        $this->_httpClient = $client;
    }

    /**
     * @return object|Client
     */
    protected function getHttpClient()
    {
        if (empty($this->_httpClient)) {
            $this->_httpClient = \Yii::createObject(['class' => Client::class, 'baseUrl' => $this->baseUrl]);
        }
        return $this->_httpClient;
    }

    /**
     * @return Request|Response|Client
     */
    public function createApiRequest()
    {
        $request = $this->getHttpClient()
                        ->createRequest()
                        ->addOptions([
                                         'timeout'       => 30,
                                         'sslVerifyPeer' => true,
                                     ]);
        return $request;
    }

    /**
     * @param $post Post
     * @return Response
     */
    abstract public function publish($post);
}