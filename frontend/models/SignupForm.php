<?php

namespace frontend\models;

use common\components\Config;
use common\models\User;
use yii\base\Model;

/**
 * Signup form
 * @property mixed full_name
 */
class SignupForm extends Model
{
    public $full_name;
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
            [['email', 'full_name'], 'required'],
            ['email', 'email', 'checkDNS' => true],
            [['email', 'full_name'], 'string', 'max' => 255],
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
        $user->full_name    = $this->full_name;
        $user->email        = $this->email;
        $user->avatar_url   = \Yii::$app->getView()->getImageUrl('user-icon.png');
        $user->status       = Config::get(Config::CONFIG_USER_EMAIL_CONFIRM, false) ? User::STATUS_DISABLE : User::STATUS_ENABLE;
        $user->password     = $this->password;
        $user->confirmation = $this->confirmation;
        //$user->login();

        return $user->save() ? $user : null;
    }
}
