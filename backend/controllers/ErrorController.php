<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\controllers;

use common\models\Error;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ErrorController extends BackendController
{
    public $activeMenu = 'store';

    /**
     * @param bool|string $id
     * @return Error|Response|array|string
     * @resource Web-site | Manage Errors | error/index
     */
    public function actionIndex($id = false)
    {
        if ($id) {
            $model = $this->findModel($id);
            $model->setScenario('update');
        } else {
            $model = new Error();
        }
        $searchModel         = new Error(['scenario' => 'search']);
        $searchModel->status = Error::STATUS_NEW;

        if ($model->getId() && $this->get('fix')) {
            $model->updateAttributes(['status' => Error::STATUS_FIXED]);

            $this->addSuccess(__('Xatolik to\'g\'irlandi'));
        }

        if ($this->get('save')) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                if ($id) {
                    $this->addSuccess(__('Error {name} updated successfully', ['name' => $model->text]));
                } else {
                    $this->addSuccess(__('Error {name} created successfully', ['name' => $model->text]));
                }

                if (!$this->isAjax())
                    return $this->redirect(['index', 'id' => $model->id]);
            }
        }
        return $this->render('index', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource Web-site | Delete Error | error/delete
     */
    public function actionDelete($id)
    {
        /**
         * @var Error $model
         */
        $model = $this->findModel($id);
        if ($model->delete()) {
            $this->addSuccess(__("Error {name} has been deleted", ['name' => $model->text]));
            return $this->redirect(['index']);
        }
    }


    /**
     * @param $id
     * @return null|Error
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Error::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested post does not exist.');
        }
    }

}
