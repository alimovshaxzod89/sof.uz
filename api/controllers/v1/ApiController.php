<?php

namespace api\controllers\v1;


use common\components\Config;
use common\models\GcmUser;
use common\models\User;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\RateLimiter;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class ApiController extends BaseController
{

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class'         => ContentNegotiator::class,
                'languages'     => Config::getLanguageLocales(),
                'languageParam' => 'l',
                'formats'       => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'rateLimiter'       => [
                'class' => RateLimiter::className(),
            ],
        ];
    }

    public function beforeAction($action)
    {

        $request = Yii::$app->getRequest();

        if ($accessToken = $request->get('t')) {
            if ($hash = $request->get('h')) {
                if ($this->validateHash($accessToken, $hash)) {
                    return parent::beforeAction($action);
                }
            }
        }

        if (YII_DEBUG || 1) {
            return parent::beforeAction($action);
        } else {
            throw new UnauthorizedHttpException(__('You are requesting with an invalid credential.'));
        }
    }

    private function validateHash($token, $hash)
    {
        return $hash == md5($token . '#_#' . $token) || YII_DEBUG;
    }


}