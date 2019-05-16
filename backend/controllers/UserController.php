<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\controllers;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class UserController extends BackendController
{
    public $activeMenu = 'users';

    /**
     * @return string
     * @resource Users | Manage Users | user/index, user/view
     */
    public function actionIndex()
    {
        $searchModel = new User(['scenario' => 'search']);

        return $this->render('index', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @resource Users | Manage Users | user/create, user/update
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => 'insert']);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->addSuccess(__('User {name} created successfully', ['name' => $model->fullname]));

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->addSuccess(__('User {name} updated successfully', ['name' => $model->fullname]));

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @resource Users | Delete User | user/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {

                $this->addSuccess(__('User {name} deleted successfully', ['name' => $model->fullname]));
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());

            return $this->redirect(['update', 'id' => $model->id]);
        }


        return $this->redirect(['index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
