<?php

namespace frontend\controllers;

use common\models\Page;
use common\models\Post;
use frontend\models\PostProvider;
use Yii;
use yii\web\NotFoundHttpException;

class PageController extends BaseController
{
    public $layout = 'site';

    public function behaviors()
    {
        $post = $this->findModel($this->get('slug', 'loyiha-haqida'));

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
                    Yii::$app->request->hostName,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    $post ? $post->url : '',
                    $post ? $post->updated_at->getTimestamp() : '',
                    minVersion(),
                ],
            ],
        ];
    }

    public function actionView($slug = 'loyiha-haqida')
    {
        $model = $this->findModel($slug);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * @param $slug
     * @return array|null|PostProvider
     * @throws NotFoundHttpException
     */
    protected function findModel($slug)
    {
        if ($slug) {
            if ($model = Page::find()->where(['url' => $slug, 'status' => Post::STATUS_PUBLISHED])->one()) {
                return $model;
            }
            throw new NotFoundHttpException('Page not found');
        }

    }


}
