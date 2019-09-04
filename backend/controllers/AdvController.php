<?php

namespace backend\controllers;

use common\models\Ad;
use common\models\Stat;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AdvController extends BackendController
{
    public $activeMenu = 'adv';

    /**
     * @return string
     * @resource Advertising | Manage Ads | adv/index
     */
    public function actionIndex()
    {
        $searchModel = new Ad();

        return $this->render('index', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @resource Advertising | Manage Ads | adv/stat
     */
    public function actionStat($id)
    {
        $model       = $this->findModel($id);
        $searchModel = new Stat();

        return $this->render('stat', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->getAdStatistics($model, Yii::$app->request->post()),
        ]);
    }

    /**
     * @param bool $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @resource Advertising | Edit Ads | adv/edit
     */
    public function actionEdit($id = false)
    {
        $model = $id ? $this->findModel($id) : new Ad();
        $model->setScenario($id ? Ad::SCENARIO_UPDATE : Ad::SCENARIO_INSERT);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->checkStatus();
            $model->checkLimit();
            $this->addSuccess(
                __('Advertising `{title}` {action} successfully.', [
                    'title'  => $model->title,
                    'action' => $id ? "updated" : "created",
                ])
            );

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource Advertising | Delete Ads | adv/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {

                $this->addSuccess(
                    __('Advertising `{title}` deleted successfully.', [
                        'title' => $model->title
                    ])
                );
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }


        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return null|Ad
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Ad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested ad does not exist.');
        }
    }

}
