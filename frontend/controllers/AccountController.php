<?php

namespace frontend\controllers;

use frontend\components\AuthHandler;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\authclient\ClientInterface;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

/**
 * Account controller
 */
class AccountController extends BaseController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth'    => [
                'class'           => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * @param ClientInterface $client
     */
    public function onAuthSuccess(ClientInterface $client)
    {
        /** @var AuthHandler $authHandler */
        $authHandler = Yii::createObject([
                                             'class'  => AuthHandler::class,
                                             'client' => $client,
                                         ]);
        $authHandler->handle();

    }

    /**
     * Logs in a user.
     * @return mixed
     */
    public function actionLogin()
    {
        $this->handleFailure();

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginModel = new LoginForm();
        $regModel   = new SignupForm();
        if ($loginModel->load(Yii::$app->request->post())) {
            if ($loginModel->login()) {
                $this->addSuccess(__('Tizimga muvaffaqqiyatli kirdingiz.'));
                return $this->goBack();
            } else {
                $loginModel->password = '';
                $this->addError(__('Email yoki kalit so\'z xato!'));
            }
        }

        return $this->render('login', ['model' => $loginModel, 'regModel' => $regModel]);
    }

    /**
     * Signs user up.
     * @return mixed
     */
    public function actionSignup()
    {
        $this->handleFailure();

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $loginModel = new LoginForm();
        $regModel   = new SignupForm();

        if ($regModel->load(Yii::$app->request->post())) {
            if ($user = $regModel->signup()) {
                if (Yii::$app->getUser()->login($user, Yii::$app->params['user.loginDuration'])) {
                    $this->addSuccess(__('Siz ro\'yhatdan o\'tdingiz.'));
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', ['loginModel' => $loginModel, 'model' => $regModel]);
    }

    /**
     * Requests password reset.
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->addSuccess(__('Check your email for further instructions.'));

                return $this->redirect(['account/login']);
            } else {
                $this->addError(__('Sorry, we are unable to reset password for the provided email address.'));
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            $this->addError($e->getMessage());
            return $this->redirect(['account/login']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            $this->addSuccess(__('Your password changed successfully'));
            return $this->redirect(['account/login']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->setReturnUrl(Yii::$app->request->referrer);
        Yii::$app->user->logout();
        $this->addSuccess(__('Siz tizimdan chiqdingiz.'));
        return $this->goBack(Yii::$app->request->referrer);
    }

}
