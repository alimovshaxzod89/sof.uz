<?php

namespace frontend\models;

use common\components\Config;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 * @property mixed fullname
 */
class SignupForm extends Model
{
    public $fullname;
    public $email;
    public $password;
    public $confirmation;
    public $agree = false;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => __('This email has already been taken.')],
            ['email', 'trim'],
            [['email', 'fullname'], 'required'],
            ['email', 'email', 'checkDNS' => true],
            [['email', 'fullname'], 'string', 'max' => 255],
            [['confirmation'], 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => __('Confirmation does not match')],
            ['password', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'agree' => __('Barcha talab va qoidalar bilan tanish chiqdim va roziman'),
        ];
    }

    /**
     * Signs user up.
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user               = new User();
        $user->fullname     = $this->fullname;
        $user->email        = $this->email;
        $user->avatar_url   = \Yii::$app->getView()->getImageUrl('user-icon.png');
        $user->status       = Config::get(Config::CONFIG_USER_EMAIL_CONFIRM, false) ? User::STATUS_DISABLE : User::STATUS_ENABLE;
        $user->password     = $this->password;
        $user->confirmation = $this->confirmation;
        //$user->login();

        return $user->save() ? $user : null;
    }
}
