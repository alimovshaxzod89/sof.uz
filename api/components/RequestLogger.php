<?php
/**
 * Created by PhpStorm.
 * User: complex
 * Date: 6/22/15
 * Time: 10:50 AM
 */

namespace api\components;


use Yii;
use yii\base\Event;
use yii\web\Response;

class RequestLogger
{
    public static function logRequest(Event $event)
    {
        /**
         * @var $response Response
         */
        $response = $event->sender;

        if (is_array($response->data) && Yii::$app->controller->id != 'v1/stat') {
            if (!$response->getIsSuccessful()) {
                unset($response->data['type']);
                unset($response->data['name']);
                unset($response->data['code']);
            }
            $data = is_array($response->data) ? $response->data : [];

            $data = array_merge($data, [
                'success' => $response->getIsSuccessful(),
                'status'  => $response->statusCode,
            ]);

            $response->data       = $data;
            $response->statusCode = 200;

            Yii::trace(print_r($data, true), 'request');
        }
    }
}