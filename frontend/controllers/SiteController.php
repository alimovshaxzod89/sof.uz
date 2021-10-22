<?php

namespace frontend\controllers;

use frontend\models\ContactForm;
use frontend\models\ErrorForm;
use frontend\widgets\SidebarWeather;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id == 'type') {
            Yii::$app->request->enableCsrfValidation = false;
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\PageCache',
                'only' => ['index', 'browser'],
                'duration' => 600,
                'enabled' => !YII_DEBUG,
                'variations' => [
                    $this->getFlashes(),
                    Yii::$app->request->hostName,
                    Yii::$app->id,
                    Yii::$app->language,
                    intval($this->get('load')),
                    intval($this->get('ml')),
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    minVersion(),
                ],
            ],
        ];
    }

    public function actionApple()
    {
        header('Content-Type: application/pkcs7-mime');
        echo '{
  "applinks": {
    "apps": [],
    "details": [
      {
        "appID": "H5VVCZ32P5.simplesolutions.sof.uz",
        "paths": [
          "*"
        ]
      }
    ]
  }
}';
        die;
    }
    /**
     * Displays homepage.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    /**
     * Displays contact page.
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->addSuccess(__('Biz bilan bog\'langaningiz uchun rahmat. Tez orada sizga javob beramiz.'));
            } else {
                $this->addError(__('Xabar jo\'natishda hatolik, keyinroq qayta urinib ko\'ring'));
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * Displays about page.
     * @return mixed
     */
    public function actionBrowser()
    {
        $this->layout = 'main';
        return $this->render('browser');
    }

    /**
     * Sends typo.
     * @param $q
     * @return mixed
     */
    public function actionTypo($q = '')
    {
        $result = false;
        $model = new ErrorForm();
        $model->text = strip_tags($q);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->addSuccess(__('Xato haqida xabar jo\'natildi'));
            $model->sendEmail();
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('@frontend/views/layouts/partials/error', ['result' => $result, 'model' => $model, 'q' => $q]);
    }

    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;

        if ($exception instanceof NotFoundHttpException) {
            return $this->render('404', ['exception' => $exception]);
        } else {

            return $this->render('500', ['exception' => $exception]);
        }
    }

    public function actionDom()
    {
        return Yii::$app->cache->flush();
    }
}
