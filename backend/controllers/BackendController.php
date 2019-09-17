<?php

namespace backend\controllers;

use backend\components\ContextInterface;
use backend\components\FilterAccessControl;
use common\components\SystemLog;
use common\models\Admin;
use common\models\Login;
use common\models\Post;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\IdentityInterface;
use yii\web\Response;

class BackendController extends Controller implements ContextInterface
{
    public $layout     = 'urban-dashboard';
    public $activeMenu = 'responses';

    /**
     * @var Admin|IdentityInterface
     */
    protected $_user;

    public function beforeAction($action)
    {

        if ($action->id != 'error')
            if ($duration = (intval(getenv('BACK_LOCK_TIME')) * 60)) {
                $start = getenv('BACK_LOCK_START');
                if ($start) {
                    if ($start = date_create_from_format('d-m-Y H:i', $start)) {
                        if (time() < $start->getTimestamp()) {
                            if (!Yii::$app->request->isAjax)
                                $this->addError(__('Migration scheduled on {date}, after {minute} minutes backend stops permanently!', ['date' => "<b>" . Yii::$app->formatter->asDatetime($start->getTimestamp()) . "</b>", 'minute' => ceil(($start->getTimestamp() - time()) / 60)]));
                        } else {
                            $has = $start->getTimestamp() + $duration - time();
                            if ($has >= 0) {
                                echo __('Migration going, it completes after {minute} minutes', ['minute' => ceil($has / 60)]);
                                return false;
                            }
                        }
                    } else {
                        echo "BACK_LOCK_START format incorrect, use as 09-02-2018 02:10\n";
                    }
                }
            }

        if (in_array($action->id, ['photo', 'video', 'index', 'draft'])) {
            $path = $this->id . '_' . $action->id . '_search';
            if (Yii::$app->request->isAjax) {
                $params = $this->get();
                foreach ($params as $key => $param) {
                    if (is_array($param) && array_key_exists('search', $param)) {
                        Yii::$app->session->set($path, $params);
                    }
                }
            } else {
                $params = Yii::$app->session->get($path);

                Yii::$app->request->setQueryParams($params);
            }

        }
        $controller = Yii::$app->controller;


        if (Yii::$app->request->isGet && !Yii::$app->request->isAjax && !Yii::$app->user->isGuest) {
            $user = $this->_user();

            if ($controller->id == 'post' && $action->id == 'edit') {

            } else {
                /*$lockers = Post::getLockedPosts($user);
                foreach ($lockers as $locked) {
                    $timestamp = ($locked->locked_on instanceof Timestamp) ? $locked->locked_on->getTimestamp() : time();
                    $message   = __('You have locked the post {b}"{title}"{bc} on {date}. {b}{action}{bc}', [
                        'title'  => $locked->getTitleView(),
                        'date'   => Yii::$app->formatter->asDatetime($timestamp, 'php:d M H:i'),
                        'action' => Html::a(__('Click the link to release it.'), ['post/edit', 'id' => $locked->getId(), 'release' => 1, 'return' => \yii\helpers\Url::current()]),
                    ]);
                    $this->addError($message);
                }*/
            }

        }

        return parent::beforeAction($action);
    }

    /**
     * @return Admin|IdentityInterface|Response
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

    /**
     * @inheritdoc
     */
    /*public function actions()
    {
        return [
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => FilterAccessControl::className(),
            ],
        ];
    }

    protected $_allowedActions = array(
        'login',
        'logout',
        'reset',
        'denied',
        'error',
    );

    protected function addSuccess($message)
    {
        Yii::$app->session->addFlash('success', $message);
        SystemLog::captureAction($message);
    }

    protected function addInfo($message)
    {
        Yii::$app->session->addFlash('info', $message);
    }

    protected function addWarning($message)
    {
        Yii::$app->session->addFlash('warning', $message);
    }

    protected function addError($message)
    {
        Yii::$app->session->addFlash('danger', $message);
    }

    public function actionError()
    {
        if (Yii::$app->user->isGuest)
            return Yii::$app->user->loginRequired();

        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }

    public function getPost($name = null)
    {
        return Yii::$app->request->post($name);
    }

    protected function post($name = null, $default = null)
    {
        return Yii::$app->request->post($name, $default);
    }

    protected function get($name = null, $default = null)
    {
        return Yii::$app->request->get($name, $default);
    }

    public function isAjax()
    {
        return Yii::$app->request->isAjax;
    }

    public function isPjax()
    {
        return Yii::$app->request->isPjax;
    }

    protected function handleFailure()
    {
        if (
            Login::find()
                 ->where(['ip' => Yii::$app->request->getUserIP(), 'status' => Login::STATUS_FAIL, 'type' => Login::TYPE_ADMIN])
                 ->andWhere(['$gte', 'created_at', new Timestamp(1, time() - 60)])
                 ->count() >= 5
        ) {
            echo __('So much login fails, please, don\'t try unauthorized access');
            die;
        }
    }

}
