<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace frontend\controllers;

use common\models\Blogger;
use common\models\Category;
use common\models\Tag;
use frontend\models\CategoryProvider;
use Yii;
use yii\web\HttpException;
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
        $feed      = Yii::$app->controller->action->id == 'feed' ? $this->getFeedType() : false;
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
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['feed'],
                'duration'   => 60,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    $this->getFlashes(),
                    Yii::$app->id,
                    Yii::$app->language,
                    Yii::$app->request->hostName,
                    Yii::$app->user->isGuest ? -1 : Yii::$app->user->id,
                    intval($this->get('load')),
                    $feed,
                    minVersion(),
                ],
            ],
        ];
    }

    private $feedType;

    private function getFeedType()
    {
        if ($this->feedType == null) {
            $type = $this->get('slug');

            if (!in_array($type, ['yangiliklar', 'minbarda', 'ommabop'])) {
                throw new NotFoundHttpException('Page not found');
            }

            $this->feedType = $type;
        }

        return $this->feedType;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function actionView($slug)
    {
        $category                            = $this->findModel($slug);
        $this->getView()->params['category'] = $category;

        $categoryView = file_exists(Yii::getAlias('@frontend/views/category/') . $category->slug . '.php');

        return $this->render($categoryView ? $category->slug : 'view', [
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

    public function actionAuthor($slug)
    {
        if ($model = $this->findAuthor($slug)) {
            return $this->render('feed/author', ['model' => $model]);
        }

        throw new NotFoundHttpException(404);
    }

    private function findTag($slug)
    {
        if ($slug) {
            return Tag::find()->where(['slug' => $slug])->one();
        }
    }

    private function findAuthor($slug)
    {
        if ($slug) {
            return Blogger::find()->where(['slug' => $slug])->one();
        }
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
     * @param $type
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionFeed($type = false)
    {
        $type  = $this->getFeedType();
        $model = $this->findModel('yangiliklar');
        if ($type == 'yangiliklar') {
            $this->getView()->params['category'] = $model;
        }

        return $this->render("feed/$type", ['type' => $type, 'model' => $model]);
    }

    /**
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionChoice()
    {
        return $this->render('choice');
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
