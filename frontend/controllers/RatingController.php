<?php

namespace frontend\controllers;

use common\models\Rating;
use Yii;
use yii\web\NotFoundHttpException;

class RatingController extends BaseController
{

    public function behaviors()
    {
        $post = $this->findModel($this->get('url'));

        return [
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['view'],
                'duration'   => 60,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    $this->getFlashes(),
                    Yii::$app->id,
                    Yii::$app->language,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    $post ? $post->url : '',
                    $post ? $post->updated_at->sec : '',
                    intval($this->get('load')),
                ],
            ],
        ];
    }

    public function actionView($url)
    {
        $model = $this->findModel($url);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @param $slug
     * @return array|null|Rating
     * @throws NotFoundHttpException
     */
    protected function findModel($slug)
    {
        if ($slug) {
            if ($model = Rating::find()->where(['url' => $slug, 'status' => Rating::STATUS_ACTIVE])->one()) {
                return $model;
            }
            throw new NotFoundHttpException('Page not found');
        }

    }


}
