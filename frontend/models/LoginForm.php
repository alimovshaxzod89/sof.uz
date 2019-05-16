<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace frontend\models;


use common\models\Login;
use common\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{

    public $email;
    public $password;
    public $reCaptcha;
    public $rememberMe = false;

    private $_user = false;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'    => __('Email'),
            'password' => __('Password'),
        ];
    }


    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, __('Invalid Email or Password'));
            }
        }
    }


    public function login()
    {
        $login  = new Login([
                                'ip'     => Yii::$app->request->getUserIP(),
                                'login'  => $this->email,
                                'status' => Login::STATUS_FAIL,
                                'type'   => Login::TYPE_USER,
                            ]);
        $result = false;

        if ($this->validate()) {
            $login->status = Login::STATUS_SUCCESS;
            $result        = Yii::$app->user->login($this->getUser(), !$this->rememberMe ? 3600 * 24 * 30 : Yii::$app->params['user.loginDuration']);
        }
        $login->save();

        return $result;
    }


    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

}