<?php

namespace backend\controllers;

use common\models\Tag;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TagController extends BackendController
{
    public $activeMenu = 'news';

    /**
     * @param bool|string $id
     * @return Tag|Response|array|string
     * @throws NotFoundHttpException
     * @resource News | Manage Tags | tag/index
     */
    public function actionIndex($id = false)
    {
        $model = $id ? $model = $this->findModel($id) : new Tag(['scenario' => 'insert']);
        $model->setScenario($id ? Tag::SCENARIO_UPDATE : Tag::SCENARIO_INSERT);
        $searchModel = new Tag(['scenario' => Tag::SCENARIO_SEARCH]);

        if ($this->get('save')) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $this->addSuccess(
                    __('Tag {name} {action} successfully', [
                        'name'   => $model->name,
                        'action' => __($id ? 'updated' : 'created'),
                    ])
                );

                if (!$this->isAjax())
                    return $this->redirect(['index', 'id' => $model->id]);
            }
        }
        return $this->render('index', [
            'model'        => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
        ]);
    }

    /**
     * @resource News | Manage Tags | tag/add
     * @return array|mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAdd()
    {
        $model                      = new Tag(['scenario' => Tag::SCENARIO_INSERT]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data                       = [
            $model->formName() => $this->post('data'),
        ];
        if ($model->load($data) && $model->save()) {
            return [
                'value' => $model->getId(),
                'text'  => $model->name,
            ];
        }
        return $this->post();
    }

    /**
     * @param $query
     * @return array
     */
    public function actionFetch($query)
    {
        $tags = Tag::find()
                   ->andFilterWhere(['like', 'name', $query])
                   ->all();

        $result                     = array_map(function (Tag $tag) {
            return [
                'value' => $tag->getId(),
                'text'  => $tag->name,
            ];
        }, $tags);
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $result;
    }

    /**
     * @param $id
     * @param $attribute
     * @resource News | Manage Tags | tag/change
     * @return bool
     */
    public function actionChange($id, $attribute)
    {
        $tag = $this->findModel($id);
        if ($tag->hasAttribute($attribute)) {
            if ($tag->$attribute) {
                $tag->$attribute = false;
                $tag->save(false);
            } else {
                $tag->$attribute = true;
                $tag->save(false);
            }
            return !$tag->hasErrors();
        }
        return false;
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource News | Manage Tags | tag/delete
     */
    public function actionDelete($id)
    {
        /**
         * @var Tag $model
         */
        $model = $this->findModel($id);
        if ($model->delete()) {
            $this->addSuccess(__("Tag {name} has been deleted", ['name' => $model->name]));
            return $this->redirect(['index']);
        }
    }


    /**
     * @param $id
     * @return null|Tag
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Tag::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested post does not exist.');
        }
    }

}
