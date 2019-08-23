<?php

namespace frontend\controllers;

use common\models\Comment;
use Yii;
use yii\web\Response;

class CommentController extends BaseController
{

    public function actionHandle($id = false)
    {
        /**
         * @var $model Comment
         */
        $post = $this->post();

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($user = $this->_user()) {
            $model = false;
            if ($id) {
                $model = Comment::find()
                                ->where([
                                            '_id'   => $id,
                                            '_user' => $user->getId(),
                                        ])
                                ->one();
            }

            if ($model == null) {
                $model               = new Comment();
                $model->upvote_count = 0;
                $model->_post        = $this->get('post');
            }

            $model->pings     = isset($post['pings']) ? $post['pings'] : [];
            $model->edited_at = new \MongoTimestamp(time());
            $model->text      = strip_tags($post['content']);
            $model->status    = Comment::STATUS_NEW;
            $model->_parent   = $post['parent'];
            $model->_user     = $user->getId();

            if ($model->save()) {
                return [
                    'result' => [
                        'id'                      => $model->getId(),
                        'parent'                  => $model->_parent ?: null,
                        'created'                 => $model->getShortFormattedDate('created_at'),
                        'timestamp'               => $model->created_at->sec,
                        'modified'                => $model->getShortFormattedDate('edited_at'),
                        'modified_time'           => $model->edited_at->sec,
                        'content'                 => $model->text,
                        'pings'                   => $model->pings,
                        'creator'                 => $model->user->getId(),
                        'fullname'                => $model->user->getFullname(),
                        'profile_picture_url'     => $model->user->avatar_url ?: 'https://viima-app.s3.amazonaws.com/media/user_profiles/user-icon.png',
                        'created_by_admin'        => false,
                        'created_by_current_user' => $post['creator'] == $model->user->getId(),
                        'upvote_count'            => $model->upvote_count ?: 0,
                        'user_has_upvoted'        => in_array($model->user->getId(), $model->votes ?: []),
                    ]];
            }
        }

        return [
            'result' => false,
        ];
    }

    public function actionUpvote($id)
    {
        /**
         * @var $comment Comment
         */
        $comment = Comment::findOne($id);
        $result  = false;

        if ($comment != null && (Yii::$app->user->getIsGuest() || $comment->_user != Yii::$app->user->identity->getId())) {
            $result = $comment->upVote($this->post(), Yii::$app->user->getIsGuest() ? $this->getUserId() : Yii::$app->user->identity->getId());
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => $result];
    }

    public function actionDelete($id)
    {
        $comment = Comment::findOne($id);
        $result  = false;

        if ($comment && count($comment->replies) == 0 && $comment->_user == Yii::$app->user->identity->getId()) {
            $result = $comment->delete();
            $comment->post->updateCommentCount();
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['result' => $result];
    }

}