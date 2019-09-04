<?php
namespace api\controllers\v1;

use common\components\Config;
use common\models\User;
use Yii;
use yii\rest\Controller;
use yii\web\IdentityInterface;
use yii\web\Response;

class BaseController extends Controller
{
    protected $_user = false;

    public function beforeAction($action)
    {
        $languages = Config::getLanguageOptions();

        if (($lang = $this->get('l')) && isset($languages[$lang])) {
            Yii::$app->language = $lang;
        }

        return parent::beforeAction($action);
    }

    protected function get($param = null, $default = null)
    {
        return Yii::$app->request->get($param, $default);
    }

    protected function post($param = null, $default = null)
    {
        return Yii::$app->request->post($param, $default);
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

}