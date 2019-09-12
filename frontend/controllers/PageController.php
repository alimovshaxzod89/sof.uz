<?php

namespace frontend\controllers;

use common\models\Page;
use Yii;
use yii\web\NotFoundHttpException;

class PageController extends BaseController
{
    public $layout = 'site';

    public function actionView($slug)
    {
        $model = $this->findModel($slug);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $slug
     * @return Page|\yii\mongodb\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($slug)
    {
        if ($slug) {
            if ($model = Page::find()->where(['slug' => $slug, 'type' => Page::TYPE_PAGE, 'status' => Page::STATUS_PUBLISHED])->one()) {
                return $model;
            }
            throw new NotFoundHttpException('Page not found');
        }

    }


}
