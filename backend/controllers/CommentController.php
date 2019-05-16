<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\controllers;

use common\components\SystemLog;
use common\models\Comment;
use MongoDB\BSON\ObjectId;
use Yii;
use yii\base\Exception;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class CommentController extends BackendController
{
    public $activeMenu = 'store';

    /**
     * @return string
     * @throws NotFoundHttpException
     * @resource Web-site | Manage Comments | comment/index
     */
    public function actionIndex()
    {
        $searchModel = new Comment(['scenario' => 'search']);
        if ($this->isAjax()) {
            if ($approve = $this->get('approve', false)) {
                $model         = $this->findModel($approve);
                $model->status = Comment::STATUS_APPROVED;
                if ($model->save(false)) {
                    $this->addSuccess(__('{comment} approved successfully', ['comment' => StringHelper::truncateWords($model->text, 8)]));
                    SystemLog::captureAction(__('{comment} approved successfully', ['comment' => StringHelper::truncateWords($model->text, 8)]));
                    $model->post->updateCommentCount();
                }
            }
            if ($reject = $this->get('reject', false)) {
                $model         = $this->findModel($reject);
                $model->status = Comment::STATUS_REJECTED;
                if ($model->save(false)) {
                    $this->addWarning(__('{comment} rejected successfully', ['comment' => StringHelper::truncateWords($model->text, 8)]));
                    SystemLog::captureAction( __('{comment} rejected successfully', ['comment' => StringHelper::truncateWords($model->text, 8)]));
                    $model->post->updateCommentCount();
                }
            }

            if ($id = $this->get('edit')) {
                $model = $this->findModel($id);
                return $this->renderAjax('update', compact('model'));
            }

            if ($id = $this->get('save')) {
                $model = $this->findModel($id);
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    $model->post->updateCommentCount();
                    $this->addSuccess(__('Comment {id} updated successfully', ['id' => $model->getId()]));
                    SystemLog::captureAction(__('{comment} updated successfully', ['comment' => StringHelper::truncateWords($model->text, 8)]));
                }
            }

        }
        return $this->render('index', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @param $id
     * @return array|string|Response
     * @resource Web-site | Manage Comments| comment/edit
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->post->updateCommentCount();
            $this->addSuccess(__('Comment {id} updated successfully', ['id' => $model->getId()]));
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
     * @resource Web-site | Delete Comments | comment/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {
                $model->post->updateCommentCount();
                $this->addSuccess(__('Comment {title} deleted successfully', ['title' => $model->text]));
                SystemLog::captureAction(__('{comment} deleted successfully', ['comment' => StringHelper::truncateWords($model->text, 8)]));
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }

        if (Yii::$app->request->isAjax) {
            return $this->actionIndex();
        }

        return $this->redirect(['index']);
    }


    /**
     * @param $id
     * @return null|Comment
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne(new ObjectId($id))) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested comment does not exist.');
        }
    }

}
