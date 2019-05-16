<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/20/17
 * Time: 10:09 PM
 */

namespace frontend\controllers;


use common\models\Poll;
use frontend\models\PollProvider;
use frontend\widgets\SidebarPoll;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PollController extends BaseController
{
    public function actionIndex($type = 'all', $id = false)
    {
        if (!in_array($type, ['all', 'active', 'expired'])) {
            throw new NotFoundHttpException('Page not found');
        }


        if ($type == 'all') {
            $condition = ['status' => ['$ne' => PollProvider::STATUS_DISABLE]];
        } else if ($type == 'expired') {
            $condition = ['status' => Poll::STATUS_EXPIRE];
        } else {
            $condition = ['status' => Poll::STATUS_ENABLE];
        }


        if ($id) {
            $model = $this->findModel($id);
        } else {
            $model = Poll::find()
                         ->where($condition)
                         ->orderBy(['created_at' => SORT_DESC])
                         ->one();
        }

        if ($id) {
            if ($model)
                $condition['_id'] = ['$nin' => [$model->_id]];
        } else {
            if ($model)
                $condition['_id'] = ['$nin' => [$model->_id]];
        }

        return $this->render('index', [
            'type'      => $type,
            'model'     => $model,
            'condition' => $condition,
        ]);
    }

    public function actionVote($id)
    {
        $model = $this->findModel($id);
        $vote  = intval($this->post('poll'));

        $model->upVote($vote, $this->getUserId());

        if (\Yii::$app->request->isAjax) {
            if ($this->get('sidebar')) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'success' => true,
                    'result'  => $this->renderPartial('@frontend/views/poll/sidebar', ['poll' => $model]),
                ];
            }

            return $this->actionIndex('all', $model->getId());
        }

        return $this->redirect(['polls/all/' . $model->getId()]);

    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->renderPartial('@frontend/views/poll/sidebar.php', ['poll' => $model]);
    }

    /**
     * @param $id
     * @return null|Poll
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if ($model = Poll::findOne($id)) {
            return $model;
        }
        throw new NotFoundHttpException('Page not found');
    }

}