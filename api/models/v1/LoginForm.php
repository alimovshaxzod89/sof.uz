<?php

namespace api\models\v1;

use common\models\Admin;
use common\models\Login;
use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Login form.
 */
class LoginForm extends Model
{
    public  $email;
    public  $password;
    private $_user = false;

    public function attributeLabels()
    {
        return [
            'email'    => __('Email'),
            'password' => __('Password'),
        ];
    }

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
        ];
    }

    /**
     * @return Admin|bool
     */
    public function signIn()
    {
        $login = new Login([
                               'ip'     => Yii::$app->request->getUserIP(),
                               'login'  => $this->email,
                               'status' => Login::STATUS_FAIL,
                               'type'   => Login::TYPE_USER,
                           ]);

        if ($this->validate()) {
            $login->status = Login::STATUS_SUCCESS;
            $user          = $this->getUser();
            if ($user && $user->validatePassword($this->password)) {
                return $user->login();
            }

            $this->addError('email', __('Invalid Email or Password'));
        }
        $login->save();

        return false;
    }

    public function getError()
    {
        $errors = $this->getFirstErrors();
        return array_shift($errors);
    }

    /**
     * @return User
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
