<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

namespace api\models\v1;

use common\components\Config;
use common\models\Auth;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 * @property mixed full_name
 * @property mixed email
 * @property mixed id
 * @property mixed avatar_url
 * @property mixed login
 * @property mixed client
 */
class AuthForm extends Model
{
    public $full_name;
    public $email;
    public $id;
    public $login;
    public $avatar_url;
    public $client;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            [['full_name', 'client', 'id', 'avatar_url'], 'required'],
        ];
    }

    /**
     * Signs user up.
     * @return User|null|bool the saved model or null if saving fails
     */
    public function sign()
    {
        if (!$this->validate()) {
            return null;
        }
        /** @var Auth $auth */
        $auth = Auth::findOne([
                                  'source'    => $this->client,
                                  'source_id' => $this->id,
                              ]);
        if ($auth) {
            return $auth->user;
        }
        if ($this->email !== null && User::find()->where(['email' => $this->email])->exists()) {
            $this->addError('email', 'This email already taken');
            return false;
        } elseif ($this->login !== null && User::find()->where(['login' => $this->login])->exists()) {
            $this->addError('login', 'This login already taken.');
            return false;
        } else {
            $user                  = new User();
            $user->full_name        = $this->full_name;
            $user->email           = $this->email;
            $user->avatar_url      = $this->avatar_url;
            $user->status          = User::STATUS_ENABLE;
            $user->{$this->client} = $this->login;
            $user->login           = $this->login;
            $user->setPassword(\Yii::$app->security->generateRandomString(6));
            $user->generatePasswordResetToken();
            if ($user->save()) {
                $auth = new Auth([
                                     '_user'        => $user->id,
                                     'source'       => $this->client,
                                     'source_id'    => $this->id,
                                     'source_login' => $this->login,
                                 ]);
                $auth->save();
            }
            return $user;
        }
    }

    public function getError()
    {
        $errors = $this->getFirstErrors();
        return array_shift($errors);
    }
}
