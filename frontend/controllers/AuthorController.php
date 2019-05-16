<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace frontend\controllers;

use common\models\Blogger;
use common\models\Post;
use frontend\models\PostProvider;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Author controller
 */
class AuthorController extends BaseController
{
    public function behaviors()
    {
        $author = $this->findModel($this->get('login'));
        $post   = $this->findPost($this->get('slug'));

        return [
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['index'],
                'duration'   => 600,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    Yii::$app->id,
                    Yii::$app->language,
                    Yii::$app->request->hostName,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    intval($this->get('load')),
                    minVersion(),
                ],
            ],

            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['view'],
                'duration'   => 600,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    Yii::$app->id,
                    Yii::$app->language,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    $author ? $author->login : '',
                    $author ? $author->updated_at->sec : '',
                    intval($this->get('load')),
                    minVersion(),
                ],
            ],

            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['post'],
                'duration'   => 600,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    Yii::$app->id,
                    Yii::$app->language,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    $post ? $post->url : '',
                    $post ? $post->updated_at->sec : '',
                    $author ? $author->updated_at->sec : '',
                    intval($this->get('load')),
                    minVersion(),
                ],
            ],

        ];
    }

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
        ]);
    }

    /**
     * @param $login
     * @return mixed
     */
    public function actionView($login)
    {
        $model = $this->findModel($login);

        return $this->render('view', [
            'model' => $model,
        ]);
    }


    public function actionPost($slug, $login)
    {
        $model  = $this->findPost($slug);
        $author = $this->findModel($login);

        if ($author->getId() != $model->author->getId()) {
            throw new NotFoundHttpException('Page not found');
        }

        return $this->render('@frontend/views/post/view', [
            'model'  => $model,
            'author' => $model->author,
        ]);
    }

    /**
     * @param $login
     * @return array|Blogger|null
     * @throws NotFoundHttpException
     * @internal param $slug
     */
    protected function findModel($login)
    {
        if ($login) {
            if ($model = Blogger::findByLogin($login)) {
                return $model;
            }
            throw new NotFoundHttpException('Page not found');
        }
    }

    /**
     * @param $slug
     * @return PostProvider|array|null|\yii\mongodb\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findPost($slug)
    {
        if ($slug) {
            if ($model = PostProvider::find()->where(['url' => $slug, 'status' => Post::STATUS_PUBLISHED])->one()) {
                return $model;
            }
            throw new NotFoundHttpException('Page not found');
        }
    }

}
