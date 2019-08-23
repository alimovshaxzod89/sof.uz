<?php

namespace backend\controllers;

use common\components\Config;
use common\models\Poll;
use MongoDB\BSON\ObjectId;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class PollController extends BackendController
{
    public $activeMenu = 'store';

    /**
     * @return string
     * @resource Web-site | Manage Polls | poll/index
     */
    public function actionIndex()
    {
        $searchModel = new Poll(['scenario' => 'search']);

        return $this->render('index', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @resource Web-site | Manage Polls | poll/view
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @resource Web-site | Edit Polls | poll/edit
     */
    public function actionEdit($id = false)
    {
        if ($id) {
            $model = $this->findModel($id);
        } else {
            $model = new Poll();
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }


        if ($this->get('convert')) {
            $changed = false;
            $lang    = Yii::$app->language;

            if ($lang == Config::LANGUAGE_CYRILLIC) {
                $lang    = Config::LANGUAGE_UZBEK;
                $changed = $model->syncLatinCyrill($lang, true);

            } else if ($lang == Config::LANGUAGE_UZBEK) {
                $lang    = Config::LANGUAGE_CYRILLIC;
                $changed = $model->syncLatinCyrill($lang, true);
            }

            if ($changed) {
                $this->addSuccess(__('Poll converted to {language} successfully', ['language' => Config::getLanguageLabel($lang)]));
            }

            return $this->redirect(['edit', 'id' => $model->getId(), 'language' => $lang]);
        }

        if ($model->load(Yii::$app->request->post()) && $model->updatePoll()) {
            if ($id) {
                $this->addSuccess(__('Poll {title} updated successfully', ['title' => $model->getShortTitle()]));
            } else {
                $this->addSuccess(__('Poll {title} created successfully', ['title' => $model->getShortTitle()]));
            }

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
     * @resource Web-site | Delete Polls | poll/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {

                $this->addSuccess(__('Poll {title} deleted successfully', ['title' => $model->question]));
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }


        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @resource Web-site | Reset Poll | poll/reset
     */
    public function actionReset($id)
    {
        if ($model = $this->findModel($id)) {
            if ($model->resetVotes()) {
                $this->addSuccess(__('Poll {title} reset successfully', ['title' => $model->getShortTitle()]));
            }
        }

        return $this->redirect(['poll/view', 'id' => $id]);
    }

    /**
     * @param $id
     * @return null|Poll
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Poll::findOne(new ObjectId($id))) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested poll does not exist.');
        }
    }

}
