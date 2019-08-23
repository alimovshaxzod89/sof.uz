<?php

namespace backend\controllers;

use common\components\Config;
use common\models\Page;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class PageController extends BackendController
{
    public $activeMenu = 'store';

    /**
     * @return string
     * @resource Web-site | Manage Pages | page/index
     */
    public function actionIndex()
    {
        $searchModel = new Page(['scenario' => Page::SCENARIO_SEARCH]);

        return $this->render('index', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @resource Web-site | Manage Pages | page/view
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param bool $id
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @resource Web-site | Manage Pages | page/edit
     */
    public function actionEdit($id = false)
    {
        $model = $id ? $this->findModel($id) : new Page();
        $model->setScenario($id ? Page::SCENARIO_UPDATE : Page::SCENARIO_INSERT);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }


        if ($this->get('convert')) {
            $changed = false;
            $lang    = Yii::$app->language;

            if (in_array($lang, [Config::LANGUAGE_CYRILLIC, Config::LANGUAGE_UZBEK])) {
                $lang    = $lang == Config::LANGUAGE_CYRILLIC ? Config::LANGUAGE_UZBEK : Config::LANGUAGE_CYRILLIC;
                $changed = $model->syncLatinCyrill($lang, true);
            }

            if ($changed) {
                $this->addSuccess(
                    __('Page converted to `{language}` successfully.', [
                        'language' => Config::getLanguageLabel($lang)
                    ])
                );
            }

            return $this->redirect(['edit', 'id' => $model->getId(), 'language' => $lang]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->updatePage()) {
            $this->addSuccess(
                __('Page `{title}` {action} successfully', [
                    'title'  => $model->title,
                    'action' => __($id ? 'updated' : 'created')
                ])
            );

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource Web-site | Delete Pages | page/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {
                $this->addSuccess(
                    __('Page `{title}` deleted successfully.', [
                        'title' => $model->title
                    ])
                );
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }

        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return null|Page
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}