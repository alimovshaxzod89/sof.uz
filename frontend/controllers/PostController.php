<?php

namespace frontend\controllers;

use common\models\Post;
use frontend\models\PostProvider;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Post controller
 */
class PostController extends BaseController
{
    public $layout = 'site';

    /**
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function behaviors()
    {
        $post = false;
        if (!YII_DEBUG) {
            if ($slug = $this->get('slug')) {
                $post = $this->findModel($slug);
            }

            if ($short = $this->get('short')) {
                $post = $this->findShortModel($short);
            }
        }

        Url::remember(Yii::$app->request->url);

        return [
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['view', 'short', 'old'],
                'duration'   => 60,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    $this->getFlashes(),
                    Yii::$app->request->hostName,
                    Yii::$app->id,
                    Yii::$app->language,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    $post ? $post->slug : '',
                    $post ? $post->updated_at->getTimestamp() : '',
                    intval($this->get('load')),
                    intval($this->get('oldid')),
                    minVersion(),
                ],
            ],
        ];
    }

    /**
     * @param             $slug
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView($slug)
    {
        $model                           = $this->findModel($slug);
        $this->getView()->params['post'] = $model;
        $view                            = $model->type == Post::TYPE_NEWS && !$model->is_sidebar ? 'news_sidebar' : $model->type;

        return $this->render($view, [
            'model' => $model,
        ]);
    }

    /**
     * @param $short
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionShort($short)
    {
        $model = $this->findShortModel($short);
        return $this->redirect(['view', 'slug' => $model->slug]);
    }

    /**
     * @param $slug
     * @return array|null|PostProvider
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    protected function findModel($slug)
    {
        $model = PostProvider::find()->where(['slug' => $slug, 'status' => PostProvider::STATUS_PUBLISHED])->one();
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException('Page not found');
    }

    /**
     * @param $short
     * @return array|null|PostProvider
     * @throws NotFoundHttpException
     */
    protected function findShortModel($short)
    {
        $model = PostProvider::findOne(['short_id' => $short, 'status' => Post::STATUS_PUBLISHED]);
        if ($model) {
            return $model;
        }
        throw new NotFoundHttpException('Page not found');
    }
}