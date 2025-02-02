<?php

namespace backend\controllers;

use common\models\Category;
use Yii;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CategoryController extends BackendController
{
    public $activeMenu = 'news';

    /**
     * @param $id string|boolean
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @resource News | Manage Categories | category/index
     */
    public function actionIndex($id = false)
    {
        $model = $id ? $this->findModel($id) : new Category(['scenario' => 'insert']);

        if ($this->isAjax()) {
            if ($this->get('sort')) {
                if ($data = Json::decode($this->getPost('data'), true)) {
                    return Category::sortTree($data);
                }
            }

            if ($this->get('choose', false)) {
                return $this->renderAjax('_add', ['model' => $model]);
            }

            if ($this->get('products', false)) {
                return $this->renderAjax('_products', ['model' => $model]);
            }
        }


        if ($model->load(Yii::$app->request->post())) {
            if ($parent = $this->get('parent'))
                $model->parent = $parent;
            if ($model->save()) {
                $this->addSuccess(
                    __('Category `{name}` updated successfully', [
                        'name' => $model->name
                    ])
                );
                if (!$this->isAjax())
                    return $this->redirect(['index', 'id' => $model->id]);
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }


    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     * @resource News | Delete Category | category/delete
     */
    public function actionDelete($id)
    {
        /**
         * @var Category $model
         */
        $model = $this->findModel($id);
        if ($model->delete()) {
            $this->addSuccess(
                __("Category `{name}` has been deleted.", [
                    'name' => $model->name
                ])
            );
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id = 0)
    {
        $model = Category::findOne($id);
        $model->setScenario('update');
        if (!$model)
            throw new NotFoundHttpException();
        return $model;
    }
}