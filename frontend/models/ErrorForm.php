<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

namespace frontend\models;


use common\components\Config;
use common\models\Error;
use himiklab\yii2\recaptcha\ReCaptchaValidator;
use Yii;
use yii\base\Model;

class ErrorForm extends Model
{

    public $message;
    public $url;
    public $text;

//    public $reCaptcha;

    public function rules()
    {
        return [
            [['text', 'message'], 'required'],
            [['text', 'message'], 'string', 'max' => 1000],
            ['url', 'safe'],
            //['reCaptcha', ReCaptchaValidator::className()],
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $model          = new Error();
            $model->text    = $this->text;
            $model->url     = $this->url;
            $model->message = $this->message;
            $model->status  = Error::STATUS_NEW;
            return $model->save();
        }

        return false;
    }

    public function sendEmail()
    {
        if (!YII_DEBUG) {
            $email = [
                getenv('CONTACT_EMAIL') => 'Zarnews',
            ];
            return Yii::$app->mailer->compose('frontend/typo', ['text' => $this->text, 'message' => $this->message])
                                    ->setTo($email)
                                    ->setFrom([getenv('CONTACT_EMAIL') => Yii::$app->name])
                                    ->setSubject(__("Saytda xatolik xabari"))
                                    ->setTextBody(__('Xatolik aniqlangan manzil: ') . $this->url)
                                    ->send();
        }
        return true;
    }

}