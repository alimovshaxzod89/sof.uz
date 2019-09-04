<?php

namespace backend\controllers;

use common\models\Ad;
use common\models\Place;
use Yii;
use yii\base\Exception;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class PlaceController extends BackendController
{
    public $activeMenu = 'adv';

    /**
     * @return string
     * @resource Advertising | Manage Places | place/index
     */
    public function actionIndex()
    {
        $model = new Place();

        return $this->render('index', [
            'dataProvider' => $model->search(Yii::$app->request->get()),
            'model'        => $model,
        ]);
    }

    /**
     * @param mixed $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @resource Advertising | Edit Places | place/edit
     */
    public function actionEdit($id = false)
    {
        $model = $id ? $this->findModel($id) : new Place();
        $model->setScenario($id ? Place::SCENARIO_UPDATE : Place::SCENARIO_INSERT);

        if ($this->isAjax()) {
            if ($model->load($this->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($this->get('add')) {
                if ($data = Json::decode($this->post('data'), true)) {
                    return $model->addAds($data);
                }
            }

            if ($this->get('remove')) {
                if ($data = Json::decode($this->post('data'), true)) {
                    return $model->removeAds($data);
                }
            }

            if (($p = intval($this->get('percent'))) != 0) {
                if ($ad = $this->findAdModel($this->get('ad'))) {

                    $model->changeAdPercent($ad, $p);

                    return $this->render('edit', [
                        'model'        => $model,
                        'dataProvider' => $model->getAdsProvider(),
                    ]);
                }
            }

            if ($this->get('list')) {
                return $this->renderAjax('_ad', [
                    'dataProvider' => $model->getAdsNinProvider(),
                ]);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->addSuccess(
                __('Place `{title}` {action} successfully.', [
                    'title'  => $model->title,
                    'action' => $id ? "updated" : "created",
                ])
            );

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }

        return $this->render('edit', [
            'model'        => $model,
            'dataProvider' => $model->getAdsProvider(),
        ]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource Advertising | Delete Places | place/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {

                $this->addSuccess(
                    __('Place `{title}` deleted successfully.', [
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
     *
     * @return null|Place
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Place::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested place does not exist.');
        }
    }

    /**
     * @param $id
     *
     * @return null|Ad
     * @throws NotFoundHttpException
     */
    protected function findAdModel($id)
    {
        if (($model = Ad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested ad does not exist.');
        }
    }
}