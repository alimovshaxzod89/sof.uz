<?php

namespace frontend\controllers;

use common\models\Post;
use frontend\models\PostProvider;
use MongoDB\BSON\ObjectId;
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
        $model = $this->findModel($slug);
        if ($model != null) {
            $this->getView()->params['post'] = $model;
            $view                            = !$model->is_sidebar ? 'news_sidebar' : 'news';

            return $this->render($view, [
                'model' => $model,
            ]);
        }

        throw new NotFoundHttpException('Page not found');
    }

    public function actionPreview($id, $hash = '')
    {
        $model = PostProvider::findOne(['_id' => new ObjectId($id)]);

        if ($model != null) {
            if (Yii::$app->security->validatePassword($model->id, $hash)) {

                $this->getView()->params['post'] = $model;
                $view                            = !$model->is_sidebar ? 'news_sidebar' : 'news';

                return $this->render($view, [
                    'model' => $model,
                ]);
            }
        }

        throw new NotFoundHttpException('Page not found');
    }

    /**
     * @param $short
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionShort($short)
    {
        $model = $this->findShortModel($short);
        if ($model != null) {
            $this->getView()->params['post'] = $model;
            $view                            = !$model->is_sidebar ? 'news_sidebar' : 'news';

            return $this->render($view, [
                'model' => $model,
            ]);
        }

        throw new NotFoundHttpException('Page not found');
    }

    /**
     * @param $_id
     * @param $_slug
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionOld($_id = false, $_slug = false)
    {
        $model = $this->findWithOldModel(intval($_id), $_slug);
        return $this->redirect(['view', 'slug' => $model->slug]);
    }

    /**
     * @param $slug
     * @return array|null|PostProvider
     * @throws \yii\base\InvalidConfigException
     */
    protected function findModel($slug)
    {
        return PostProvider::find()
                           ->filterWhere(['slug' => $slug, 'status' => PostProvider::STATUS_PUBLISHED])
                           ->one();
    }

    /**
     * @param $short
     * @return array|null|PostProvider
     */
    protected function findShortModel($short)
    {
        return PostProvider::findOne([
                                         'short_id' => $short,
                                         'status'   => Post::STATUS_PUBLISHED
                                     ]);
    }

    /**
     * @param bool $id
     * @param bool $slug
     * @return PostProvider|null
     * @throws NotFoundHttpException
     */
    private function findWithOldModel($id = false, $slug = false)
    {
        if ($id) {
            $model = PostProvider::findOne(['old_id' => $id, 'status' => Post::STATUS_PUBLISHED]);
            if ($model != null) {
                return $model;
            }
        } elseif ($slug) {
            $model = PostProvider::findOne(['old_slug' => $slug, 'status' => Post::STATUS_PUBLISHED]);
            if ($model != null) {
                return $model;
            }
        }


        throw new NotFoundHttpException('Page not found');
    }
}