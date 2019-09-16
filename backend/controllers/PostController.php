<?php

namespace backend\controllers;

use common\components\Config;
use common\models\Post;
use common\models\Tag;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\helpers\BaseFileHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\mongodb\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class PostController extends BackendController
{
    public $activeMenu = 'news';

    public function beforeAction($action)
    {
        if (Yii::$app->request->isAjax) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    /**
     * @param string $query
     * @return array|string
     * @resource News | Manage Posts | post/tag
     */
    public function actionTag($query)
    {
        $result = [];
        $tags   = Tag::searchTags($query);
        foreach ($tags as $tag) {
            $result[] = [
                'v' => $tag->getId(),
                't' => $tag->name,
            ];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }

    /**
     * @param bool|string $type
     * @return string
     * @resource News | Manage Posts | post/index,post/upload
     */
    public function actionIndex($type = false)
    {
        $searchModel = new Post(['scenario' => Post::SCENARIO_SEARCH]);
        if ($type && !isset(Post::getTypeArray()[$type])) {
            throw new InvalidParamException("Undefined type '$type'");
        }
        if ($type)
            $searchModel->post_type = $type;

        return $this->render('index', [
            'dataProvider' => $searchModel->search($this->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @return string
     * @resource News |  Manage Posts | post/draft
     */
    public function actionDraft()
    {
        $searchModel         = new Post(['scenario' => Post::SCENARIO_SEARCH]);
        $searchModel->status = Post::STATUS_DRAFT;

        return $this->render('index', [
            'dataProvider' => $searchModel->search($this->get()),
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @return string
     * @resource News |  Manage Posts | post/photo
     */
    public function actionPhoto()
    {
        return $this->actionIndex(Post::TYPE_GALLERY);
    }

    /**
     * @return string
     * @resource News |  Manage Posts | post/video
     */
    public function actionVideo()
    {
        return $this->actionIndex(Post::TYPE_VIDEO);
    }

    /**
     * @param $type
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @resource News | Manage Posts | post/create,file-storage/delete,file-storage/upload
     */
    public function actionCreate($type)
    {
        if (!isset(Post::getTypeArray()[$type])) {
            throw new NotFoundHttpException(__("Undefined type '$type'"));
        }
        $model = new Post(['scenario' => Post::SCENARIO_CREATE]);

        $model->type     = $type;
        $model->_creator = $this->_user()->_id;

        if ($model->updatePost()) {
            $this->addSuccess(__('Post `{type}` created successfully.', [
                'type' => $model->getTypeLabel()
            ]));

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }
    }

    /**
     * @param      $id
     * @param bool $print
     * @return array|string|Response
     * @throws NotFoundHttpException
     * @resource News | Manage Posts | post/edit,tag/fetch
     * @resource News | Publish Posts | post/publish
     * @resource News | Release Locked Posts | post/release
     * @resource News | Change Creator | post/creator
     */
    public function actionEdit($id, $print = false)
    {
        $model = $this->findModel($id);
        $model->setScenario($model->type);

        if ($print) {
            return $this->render('print', ['model' => $model]);
        }
        $user = $this->_user();


        $canEdit = true;
        if ($model->status == Post::STATUS_PUBLISHED || $model->status == Post::STATUS_AUTO_PUBLISH) {
            $canEdit = $user->canAccessToResource('post/publish');
        }

        if ($this->get('release')) {
            if ($model->_creator == $user->_id || $user->canAccessToResource('post/release')) {
                if ($model->releasePostLock($user)) {
                    $this->addSuccess(__('Post {b}"{title}"{bc} released successfully.', [
                        'title' => $model->getTitleView()
                    ]));
                }
            }

            return $this->redirect($this->get('return'));
        }


        if ($this->get('status') && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'updated' => $model->updated_on instanceof \MongoDB\BSON\Timestamp ? $model->updated_on->getTimestamp() : 0,
                'editor'  => (string)$model->_creator,
            ];
        }


        $session = Yii::$app->session->id;

        if (Yii::$app->request->isGet) {
            if (!$model->isLocked($user, $session) && $canEdit) {
                /*if ($model->locForUser($user, $session)) {
                    $message = __('You have locked the post. {b}{action}{bc}.', [
                        'title'  => $model->getTitleView(),
                        'action' => Html::a(__('Click to release it.'), [
                            'post/edit',
                            'id'      => $model->getId(),
                            'release' => 1,
                            'return'  => Url::to(['post/index'])
                        ])
                    ]);
                    $this->addInfo($message);
                };*/
            }
        }

        $locked = $model->isLocked($user, $session);

        if (Yii::$app->request->isGet) {
            /*if ($locked) {
                $message = __('Post {b}{title}{bc} has locked by {b}{user}{bc}. {action}', [
                    'title'  => $model->getShortTitle(),
                    'user'   => $model->creator->getFullName(),
                    'action' => Html::a(__('Click this link to exit editor.'), [
                        'post/index'
                    ], ['class' => 'text-bold text-underline']),
                ]);
                $this->addInfo($message);
            }*/
        }

        if ($model->status != Post::STATUS_IN_TRASH && $canEdit) {

            if ($this->get('save') && Yii::$app->request->isAjax) {
                $post = Yii::$app->request->post();

                foreach (['_creator', 'status', 'published_on', 'image', '_author', 'short_id'] as $attr) {
                    unset($post['Post'][$attr]);
                }

                if ($model->load($post)) {
                    if (!$user->canAccessToResource('post/publish')) {
                        if (in_array($model->status, [Post::STATUS_PUBLISHED, Post::STATUS_AUTO_PUBLISH])) {
                            $model->status = Post::STATUS_DRAFT;
                        }
                    }

                    if ($model->updated_on == null || $model->updated_on->getTimestamp() <= $this->get('updated_on')) {
                        $model->updatePost();
                        $reload = 0;
                    } else {
                        $reload = 1;
                    }

                    Yii::$app->response->format = Response::FORMAT_JSON;

                    return [
                        'updated' => $model->updated_on->getTimestamp(),
                        'editor'  => (string)$model->_creator,
                        'reload'  => $reload,
                    ];
                }
            }

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);
            }


            if ($this->get('convert')) {
                $changed = false;
                $lang    = Yii::$app->language;

                if ($lang == Config::LANGUAGE_CYRILLIC) {
                    $changed = $model->convertToLatin();

                    $lang = Config::LANGUAGE_UZBEK;
                } else if ($lang == Config::LANGUAGE_UZBEK) {
                    $changed = $model->convertToCyrillic();

                    $lang = Config::LANGUAGE_CYRILLIC;
                }

                if ($changed) {
                    $message = __('Post converted to `{language}` successfully.', [
                        'language' => Config::getLanguageLabel($lang)
                    ]);
                    $this->addSuccess($message);
                }

                return $this->redirect(['edit', 'id' => $model->getId(), 'language' => $lang]);
            }

            if ($model->load(Yii::$app->request->post())) {
                if (!$user->canAccessToResource('post/publish')) {
                    if (in_array($model->status, [Post::STATUS_PUBLISHED, Post::STATUS_AUTO_PUBLISH])) {
                        $model->status = Post::STATUS_DRAFT;
                    }
                } else {
                    if ($this->post('publish')) {
                        $model->status = Post::STATUS_PUBLISHED;
                    }
                }

                if (!$user->canAccessToResource('post/creator')) {
                    if ($model->isAttributeChanged('_creator')) {
                        $model->_creator = $model->getOldAttribute('_creator');
                    }
                }

                if ($model->updatePost()) {
                    if ($id) {
                        $this->addSuccess(__('Post `{title}` updated successfully.', [
                            'title'  => $model->getTitleView(),
                        ]));

                    } else {
                        $this->addSuccess(__('Post `{title}` created successfully', [
                            'title' => $model->getTitleView()
                        ]));
                    }
                }
                if ($this->post('publish')) {
                    return $this->redirect(['post/index']);
                }

                return $this->redirect(['edit', 'id' => $model->getId()]);
            }
        }

        return $this->render('edit', [
            'model'   => $model,
            'locked'  => $locked,
            'canEdit' => $canEdit,
            'type'    => $model->type,
        ]);
    }

    /**
     * @return string
     * @resource News | View Statistics | post/stat
     */
    public function actionStat()
    {
        return $this->render('stat', []);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource News | Send Push Notification | post/notification
     */
    public function actionNotification($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->sendPushNotification()) {
                $this->addSuccess(__('Push notification `{title}` sent successfully', [
                    'title' => $model->getTitleView()
                ]));
            } else {
            }
        } catch (\Exception $e) {
            $this->addError($e->getMessage());
        }


        return $this->redirect(['edit', 'id' => $model->getId()]);
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     * @resource News | Share Posts | post/share
     */
    public function actionShare($id)
    {
        $model  = $this->findModel($id);
        $social = $this->post('sharer');
        $result = false;
        if (isset(Post::getSocialArray()[$social])) {
            $result = $model->shareTo($social);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => $result];
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource News | Trash Posts | post/trash
     */
    public function actionTrash($id)
    {
        $model = $this->findModel($id);

        if ($model->toTrash()) {
            $this->addSuccess(__('Post `{title}` moved to trash successfully.', [
                'title' => $model->getTitleView()
            ]));
            return $this->redirect(Yii::$app->request->isAjax ? Yii::$app->request->getReferrer() : ['post/index']);
        }

        $this->addError(__('Post {title} moved to trash failed', ['title' => $model->getTitleView()]));
        return $this->redirect(['edit', 'id' => $model->getId()]);
    }

    /**
     * @param $id
     * @param $attribute
     * @return bool
     * @throws NotFoundHttpException
     * @resource News | Manage Posts | post/change
     */
    public function actionChange($id, $attribute)
    {
        $post = $this->findModel($id);
        if ($post->hasAttribute($attribute)) {
            $post->updateAttributes([$attribute => !boolval($post->$attribute)]);
            return !$post->hasErrors();
        }
        return false;
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource System | View Trash | post/restore
     */
    public function actionRestore($id)
    {
        $model = $this->findModel($id);

        if ($model->restoreFromTrash()) {
            $this->addSuccess(__('Post `{title}` restored successfully.', [
                'title' => $model->getTitleView()
            ]));
        }

        return $this->redirect(['post/edit', 'id' => $model->getId()]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @resource News | Delete Post | post/delete
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        try {
            if ($model->delete()) {
                $this->addSuccess(__('Post `{title}` moved to trash successfully', [
                    'title' => $model->getTitleView()
                ]));
            }
        } catch (Exception $e) {
            $this->addError($e->getMessage());

            return $this->redirect(['edit', 'id' => $model->getId()]);
        }


        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return null|Post|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested post does not exist.');
        }
    }
}