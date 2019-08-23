<?php

namespace frontend\controllers;

use common\components\Browser;
use common\models\Login;
use common\models\PollVote;
use common\models\User;
use frontend\components\ContextInterface;
use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\IdentityInterface;
use yii\web\Response;

class BaseController extends Controller implements ContextInterface
{
    protected $_user;
    protected $_userId = '_ga';
    public    $layout  = 'site';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return User|IdentityInterface|Response
     */
    public function _user()
    {
        if (!Yii::$app->user->isGuest && !$this->_user) {
            $this->_user = Yii::$app->user->identity;
            if ($this->_user == null) {
                Yii::$app->user->logout();

                return $this->goHome();
            }
        }

        return $this->_user;

    }


    protected function getFlashes()
    {
        return Yii::$app->session->get(Yii::$app->session->flashParam);
    }

    public function get($name = null, $default = null)
    {
        return Yii::$app->request->get($name, $default);
    }

    const COOKIE_DEVICE_ID = '_di';

    /**
     * @return mixed
     */
    public function getUserId()
    {
        if (Yii::$app->user->isGuest) {
            if ($cookies = Yii::$app->request->cookies->get(self::COOKIE_DEVICE_ID)) {
                return $cookies->value;
            } else {
                $browser  = new Browser();
                $deviceId = md5($browser->getBrowser() . '|' . $browser->getPlatform() . '|' . $browser->getVersion() . '|' . Yii::$app->request->getUserIP());

                Yii::$app->response->cookies->add(new Cookie([
                                                                 'name'     => self::COOKIE_DEVICE_ID,
                                                                 'value'    => $deviceId,
                                                                 'httpOnly' => true,
                                                                 'expire'   => time() + 365 * 24 * 3600,
                                                             ]));

                return $deviceId;
            }

        } else {
            return Yii::$app->user->identity->getId();
        }
    }

    /**
     * @return mixed
     */
    public function getUserVotes()
    {
        $user = $_COOKIE[$this->_userId];
        return array_map(function (PollVote $vote) {
            return new \MongoId($vote->_poll);
        }, PollVote::findAll(['_user' => $user]));
    }

    protected function post($name = null, $default = null)
    {
        return Yii::$app->request->post($name, $default);
    }


    protected function addSuccess($message)
    {
        Yii::$app->session->addFlash('success', $message);
    }

    protected function addWarning($message)
    {
        Yii::$app->session->addFlash('warning', $message);
    }

    protected function addError($message)
    {
        Yii::$app->session->addFlash('error', $message);
    }

    protected function handleFailure()
    {
        if (
            Login::find()
                 ->where(['ip' => Yii::$app->request->getUserIP(), 'status' => Login::STATUS_FAIL, 'type' => Login::TYPE_ADMIN])
                 ->andWhere(['$gte', 'created_at', new \MongoTimestamp(time() - 60)])
                 ->count() >= 5
        ) {
            echo __('So much login fails, please, don\'t try unauthorized access');
            die;
        }
    }
}