<?php

namespace frontend\controllers;

use api\models\v1\Category;
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

    public function behaviors()
    {
        $post = YII_DEBUG ? false : $this->findModel($this->get('slug'));
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
                    $post ? $post->url : '',
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
     * @param bool|string $category
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($slug, $category = false)
    {
        $model                           = $this->findModel($slug);
        $this->getView()->params['post'] = $model;

        if ($category) {
            if ($category = $this->findCategory($category)) {
                $this->getView()->params['category'] = $category;
            }
        }

        return $this->render('view', [
            'model'       => $model,
            'showReplies' => $this->get('replies', false),
        ]);
    }

    public function actionShort($slug)
    {
        return $this->actionView($slug);
        /**
         * @var $model Post
         */
        if ($model = PostProvider::findOne(['short_id' => $slug, 'status' => Post::STATUS_PUBLISHED])) {
            return $this->redirect($model->getViewUrl($model->category), 301);
        }

        throw new NotFoundHttpException();
    }

    public function actionOld($oldid)
    {
        /**
         * @var $model Post
         */
        if (intval($oldid)) {
            if ($model = PostProvider::findOne(['old_id' => intval($oldid), 'status' => Post::STATUS_PUBLISHED])) {
                return $this->redirect($model->getViewUrl(), 301);
            }
        }

        throw new NotFoundHttpException();
    }

    public function actionPreview($id, $t = '')
    {
        if ($model = PostProvider::findOne(new ObjectId($id))) {
            if ($t == md5($model->url . $model->created_at->getTimestamp())) {

                return $this->render($model->getViewTemplate(), [
                    'model'       => $model,
                    'showReplies' => $this->get('replies', false),
                ]);
            }
        };

        throw new NotFoundHttpException();
    }

    /**
     * @param $slug
     * @return array|null|PostProvider
     * @throws NotFoundHttpException
     */
    protected function findModel($slug)
    {
        if ($slug) {
            $model = PostProvider::find()
                                 ->orFilterWhere(['short_id' => $slug])
                                 ->orFilterWhere([ 'url' => $slug])
                                 ->andFilterWhere([
                                                      'status' => PostProvider::STATUS_PUBLISHED,
                                                  ])
                                 ->one();
            if ($model) {
                return $model;
            }
            throw new NotFoundHttpException('Page not found');
        }

    }

    protected function findCategory($slug)
    {
        if ($slug) {
            if ($model = Category::find()->where(['slug' => $slug])->one()) {
                return $model;
            }
            throw new NotFoundHttpException('Page not found');
        }

    }

}
