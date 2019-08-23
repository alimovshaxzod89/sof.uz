<?php

namespace backend\controllers;

use backend\models\FormUploadRating;
use common\components\Config;
use common\models\Page;
use common\models\Rating;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class RatingController extends BackendController
{
    public $activeMenu = 'store';

    /**
     * @return string
     * @resource Web-site | Manage Rating | rating/index
     */
    public function actionIndex()
    {
        $searchModel = new Rating(['scenario' => 'search']);

        return $this->render('index', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @resource Web-site | Manage Rating | rating/edit
     */
    public function actionEdit($id = false)
    {
        if ($id) {
            $model = $this->findModel($id);
        } else {
            $model = new Rating();
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
                $this->addSuccess(__('Rating converted to {language} successfully', ['language' => Config::getLanguageLabel($lang)]));
            }

            return $this->redirect(['edit', 'id' => $model->getId(), 'language' => $lang]);
        }
        $uploadModel = new FormUploadRating();
        if ($uploadModel->load(Yii::$app->request->post())) {
            if (!empty(UploadedFile::getInstance($uploadModel, 'file')))
                if ($data = $uploadModel->uploadData()) {
                    $model->updateAttributes([
                                                 'columns' => $data['cols'],
                                                 'rows'    => $data['rows'],
                                             ]);
                    $this->addSuccess(__('{count} country updated successfully', ['count' => count($data['rows'])]));
                } else if ($uploadModel->hasErrors()) {
                    $errors = $uploadModel->getFirstErrors();
                    $this->addError(array_pop($errors));
                }

        }

        if ($this->get('export', false)) {
            $rows[] = $model->columns;

            $fileName = Yii::getAlias('@runtime') . DS . DS . 'rating_' . $model->getId() . '.csv';
            if ($handle = fopen($fileName, 'w+')) {
                fputcsv($handle, $model->columns, ",", '"');
                foreach ($model->rows as $row)
                    fputcsv($handle, $row, ",", '"');
                fclose($handle);

                Yii::$app->response->sendFile($fileName);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($id) {
                $this->addSuccess(__('Rating {title} updated successfully', ['title' => $model->title]));
            } else {
                $this->addSuccess(__('Rating {title} created successfully', ['title' => $model->title]));
            }

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }

        return $this->render('edit', [
            'model'        => $model,
            'dataProvider' => $model->rowsProvider(),
            'uploadModel'  => $uploadModel,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource Web-site | Delete Rating | rating/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {

                $this->addSuccess(__('Rating {title} deleted successfully', ['title' => $model->title]));
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }


        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return null|Rating
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Rating::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
