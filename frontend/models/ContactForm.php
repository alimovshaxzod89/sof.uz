<?php

namespace frontend\models;

use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    public function attributeLabels()
    {
        return [
            'name'       => __('Ism-familya'),
            'email'      => __('Elektron pochta'),
            'subject'    => __('Mavzu'),
            'body'       => __('Xabar mazmuni'),
            'verifyCode' => __('Bot emasmisiz?'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'subject', 'body'], 'required'],
            [['subject', 'name', 'email'], 'string', 'max' => 128],
            [['body'], 'string', 'max' => 24096],
            // email has to be a valid email address
            ['email', 'email'],
            [['verifyCode'], YII_DEBUG ? 'safe' : ReCaptchaValidator::className()],
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {

        $emails = [
            getenv('CONTACT_EMAIL') => 'Minbar',
        ];

        return Yii::$app->mailer->compose([])
                                ->setTo($emails)
                                ->setFrom([getenv('CONTACT_EMAIL') => $this->name])
                                ->setSubject(strip_tags($this->subject))
                                ->setReplyTo([$this->email => $this->name])
                                ->setTextBody(strip_tags($this->body))
                                ->send();
    }
}
