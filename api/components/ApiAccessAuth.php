<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/1/17
 * Time: 11:21 PM
 */

namespace api\components;


use yii\filters\auth\AuthMethod;
use yii\web\UnauthorizedHttpException;

class ApiAccessAuth extends AuthMethod
{
    public function authenticate($user, $request, $response)
    {
        $accessHeader = $request->getHeaders()->get('X-Api-Access');
        if ($accessHeader !== null) {
            if (base64_decode($accessHeader) != \Yii::$app->params['apiAccess']) {
                $this->handleFailure($response);
            }
            return true;
        }

        return null;
    }

    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException('You are requesting with an invalid credentials.');
    }

}