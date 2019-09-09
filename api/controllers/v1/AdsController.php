<?php

namespace api\controllers\v1;

use api\models\v1\Place;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class AdsController extends BaseController
{
    public function beforeAction($action)
    {
        Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');

        return parent::beforeAction($action);
    }

    /**
     * @param $place
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGet($place)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model                      = $this->findModel($place);

        return $model->getAds($this->get('device'), $this->get('w'));
    }

    /**
     * @param $slug
     * @return null|Place
     * @throws NotFoundHttpException
     */
    protected function findModel($slug)
    {
        if (($model = Place::findOne(['slug' => $slug, 'status' => Place::STATUS_ENABLE]))) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested place does not exist.');
        }
    }
}
