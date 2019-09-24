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
            'name'       => __('Исм-фамиля'),
            'email'      => __('Електрон почта'),
            'subject'    => __('Мавзу'),
            'body'       => __('Хабар мазмуни'),
            'verifyCode' => __('Бот емасмисиз?'),
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
            !YII_DEBUG ? [['verifyCode'], ReCaptchaValidator::className(), 'uncheckedMessage' => __('Бот емасмисиз?')] : [['email'], 'safe'],
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
            getenv('CONTACT_EMAIL') => 'Sof.uz',
        ];

        return Yii::$app->mailer->compose([])
                                ->setTo($emails)
                                ->setFrom([getenv('EMAIL_LOGIN') => $this->name])
                                ->setSubject(strip_tags($this->subject))
                                ->setReplyTo([$this->email => $this->name])
                                ->setTextBody(strip_tags($this->body))
                                ->send();
    }
}
