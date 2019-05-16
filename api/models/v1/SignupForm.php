<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace api\models\v1;

use common\components\Config;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 * @property mixed fullname
 * @property mixed email
 * @property mixed password
 * @property mixed confirmation
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
        $user->avatar_url   = '';
        $user->status       = Config::get(Config::CONFIG_USER_EMAIL_CONFIRM, false) ? User::STATUS_DISABLE : User::STATUS_ENABLE;
        $user->password     = $this->password;
        $user->confirmation = $this->confirmation;
        //$user->login();

        return $user->save() ? $user : null;
    }
}
