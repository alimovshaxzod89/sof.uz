<?php

namespace frontend\controllers;

use common\models\Admin;
use common\models\Category;
use common\models\Tag;
use frontend\models\CategoryProvider;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Category controller
 */
class CategoryController extends BaseController
{
    public $layout = 'site';

    public function behaviors()
    {
        $category = Yii::$app->controller->action->id == 'view' ? $this->findModel($this->get('slug')) : false;
        $tag      = Yii::$app->controller->action->id == 'tag' ? $this->findTag($this->get('slug')) : false;

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
                    $category ? $category->slug : '',
                    $category ? $category->updated_at->getTimestamp() : '',
                    intval($this->get('load')),
                    intval($this->get('rand')),
                    minVersion(),
                ],
            ],
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['audio', 'audio', 'photo', 'author', 'tag'],
                'duration'   => 60,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    $this->getFlashes(),
                    Yii::$app->id,
                    Yii::$app->language,
                    Yii::$app->request->hostName,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    $tag ? $tag->slug : '',
                    intval($this->get('load')),
                    $this->get('slug'),
                    minVersion(),
                ],
            ],
        ];
    }

    /**
     * @param $slug
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($slug)
    {
        $category                            = $this->findModel($slug);
        $this->getView()->params['category'] = $category;

        return $this->render('view', [
            'model' => $category,
        ]);
    }

    public function actionTag($slug)
    {
        if ($tag = $this->findTag($slug)) {
            return $this->render('tag', ['model' => $tag]);
        }

        throw new NotFoundHttpException(404);
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAuthor($slug)
    {
        $model = $this->findAuthor($slug);

        return $this->render('author', [
            'model' => $model
        ]);
    }

    private function findTag($slug)
    {
        if ($slug) {
            return Tag::find()->where(['slug' => $slug])->one();
        }
    }

    private function findAuthor($slug)
    {
        if (($model = Admin::find()->where(['slug' => $slug])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Page not found');
    }

    public function actionSearch($q)
    {
        $q = trim(strip_tags($q));

        if (mb_strlen($q) > 2) {
            return $this->render('search', ['search' => $q]);
        }

        return $this->redirect(['/']);
    }

    /**
     * @param $slug
     * @return array|null|Category
     * @throws NotFoundHttpException
     */
    protected function findModel($slug)
    {
        if ($slug) {
            if ($model = CategoryProvider::find()->where(['slug' => $slug])->one()) {
                return $model;
            }
        }
        throw new NotFoundHttpException('Page not found');
    }

}
