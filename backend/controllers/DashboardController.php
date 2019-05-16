<?php

namespace backend\controllers;

use backend\models\FormAdminLogin;
use backend\models\FormAdminReset;
use Yii;
use yii\base\InvalidParamException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class DashboardController extends BackendController
{
    public $activeMenu = '';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->handleFailure();

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'dialog';
        $model        = new FormAdminLogin();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                $this->addSuccess(__('You have logged in successfully'));
                return $this->goBack();
            } else {
                $this->addError(__('Invalid Login or Password'));
            }

            return $this->redirect('login');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionProfile()
    {
        $model = $this->_user();
        $model->setScenario('profile');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->addSuccess(__('Your profile updated successfully'));

            return $this->redirect(['dashboard/profile']);
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    public function actionReset($token = false)
    {
        $this->handleFailure();

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'dialog';
        $model        = new FormAdminReset();
        $model->setScenario($token ? 'resetPassword' : 'resetRequest');


        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($token) {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                try {
                    if ($model->resetAdminPassword($token)) {
                        $this->addSuccess(__('Password changed successfully.'));

                        return $this->goHome();
                    }
                } catch (InvalidParamException $e) {
                    $this->addError($e->getMessage());
                }

                return $this->redirect(['/backend/dashboard/login']);
            }

            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->sendEmail()) {
                    $this->addSuccess(__('Check your email for further instructions.'));

                    return $this->goHome();
                } else {
                    $this->addError(__('Sorry, we are unable to reset password for email provided.'));
                }
                return $this->redirect(['/dashboard/reset']);
            }

            return $this->render('resetRequest', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            Yii::$app->session->addFlash('success', __('You have logged out'));
        }

        return $this->goHome();
    }
}
