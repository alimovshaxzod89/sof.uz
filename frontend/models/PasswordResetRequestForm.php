<?php
namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
             'targetClass' => '\common\models\User',
             'filter'      => ['status' => User::STATUS_ENABLE],
             'message'     => __('There is no user with this email address.'),
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
                                  'status' => User::STATUS_ENABLE,
                                  'email'  => $this->email,
                              ]);

        if (!$user) {
            return false;
        }

        if (!$user->isPasswordResetTokenValid()) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([getenv('CONTACT_EMAIL') => __('Xabar.uz Support')])
            ->setTo($this->email)
            ->setSubject(__('Password reset for xabar.uz'))
            ->send();
    }
}
