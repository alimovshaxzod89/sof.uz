<?php

namespace api\controllers\v1;

use api\models\v1\Category;
use api\models\v1\Post;
use common\components\Config;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class PostController extends ApiController
{
    private static $noPriorityCounter = 4;

    public function behaviors()
    {
        $data = parent::behaviors();
        if (YII_DEBUG) return $data;

        return ArrayHelper::merge($data, [
            [
                'class'              => 'yii\filters\HttpCache',
                'cacheControlHeader' => 'public, max-age=60',
                'lastModified'       => function ($action, $params) {
                    $q = new \yii\mongodb\Query();
                    return $q->from('post')->max('updated_at')->getTimestamp();
                },
            ],
            [
                'class'      => 'yii\filters\PageCache',
                'only'       => ['view', 'view-url', 'list', 'home'],
                'duration'   => 60,
                'enabled'    => !YII_DEBUG,
                'variations' => [
                    Yii::$app->id,
                    Yii::$app->language,
                    $this->get('l'),
                    $this->get('page'),
                    $this->get('category'),
                    $this->get('tag'),
                    $this->get('type'),
                    $this->get('order'),
                    $this->get('before'),
                    $this->get('after'),
                    $this->get('full'),
                    $this->get('limit'),
                    $this->get('refresh'),
                    $this->get('id'),
                    $this->get('slug'),
                    $this->get('v'),
                    $this->get('push'),
                ],
            ],
        ]);
    }

    const ORDER_DEFAULT = 'published_on';
    private static $_orders = [
        'published_on',
        'views_l3d',
        'views_l7d',
        'views_l30d',
        'views_today',
        'views',
    ];

    public function actionSearch($query, $page = 0)
    {
        $page  = intval($page);
        $limit = 30;
        $query = strip_tags($query);

        $result = Post::find()->orderBy(['published_on' => SORT_DESC]);

        $result->orFilterWhere(['title' => ['$regex' => $query, '$options' => 'si']]);
        $result->orFilterWhere(["content" => ['$regex' => $query, '$options' => 'si']]);
        $result->andFilterWhere(['status' => Post::STATUS_PUBLISHED, 'is_mobile' => true]);
        foreach (Config::getLanguageCodes() as $code) {
            $result->orFilterWhere(["_translations.title_" . $code => ['$regex' => $query, '$options' => 'si']]);
            $result->orFilterWhere(["_translations.content_" . $code => ['$regex' => $query, '$options' => 'si']]);
        }

        $result->limit($limit)
               ->offset($page * $limit);

        return [
            'items' => $result->all(),
            'page'  => $page,
            'limit' => $limit,
            'query' => $query,
        ];

    }

    public function actionList($page = 0, $category = false, $tag = false, $type = 'all', $order = self::ORDER_DEFAULT, $full = false)
    {
        $page  = intval($page);
        $limit = 15;

        $after  = intval($this->get('after', false));
        $before = intval($this->get('before', false));

        $full = ($page < 2) ? true : $full;

        if (!$order || !in_array($order, self::$_orders)) {
            $order = self::ORDER_DEFAULT;
        }

        if ($order && $order != self::ORDER_DEFAULT && $page > 3) {
            return [
                'items'    => [],
                'page'     => $page,
                'type'     => $type,
                'limit'    => $limit,
                'category' => $category ? $category->getId() : null,
                'tag'      => $tag ? $tag->getId() : null,
            ];
        }

        $posts = Post::find()
                     ->where([
                                 'status' => Post::STATUS_PUBLISHED,
                                 //'is_mobile' => true,
                             ])
                     ->limit($limit)
                     ->orderBy([$order => SORT_DESC])
                     ->offset($page * $limit);


        if ($order == 'views') {
            $date = new Timestamp(1, strtotime("-3 days"));
            $posts->andFilterWhere([
                                       'views'        => ['$gte' => 1],
                                       'published_on' => ['$gte' => $date],
                                   ])
                  ->orderBy(['views' => SORT_DESC]);
        } else {
            $posts->orderBy([$order => SORT_DESC]);
        }


        if ($category) {
            if ($category = Category::findOne($category)) {
                $posts->andWhere([
                                     '_categories' => [
                                         '$elemMatch' => [
                                             '$eq' => $category->getId(),
                                         ],
                                     ],
                                 ]);
            } else {
                $category = null;
            }
        }

        if ($tag) {
            $posts->andWhere([
                                 '_tags' => [
                                     '$elemMatch' => [
                                         '$eq' => new ObjectId($tag),
                                     ],
                                 ],
                             ]);
        }

        $result = [];

        foreach ($posts->all() as $post) {
            $item                 = $post->toArray([]);
            $item['has_priority'] = $this->getPriority($post);
            $result[]             = $item;
        }

        return [
            'items'    => $result,
            'page'     => $page,
            'type'     => $type,
            'limit'    => $limit,
            'category' => $category ? $category->getId() : null,
            'tag'      => $tag ?: null,
        ];
    }

    public function actionViewUrl($slug)
    {
        if ($post = Post::find()
                        ->orFilterWhere(['short_id' => $slug])
                        ->orFilterWhere(['slug' => $slug])
                        ->andFilterWhere(['status' => Post::STATUS_PUBLISHED])
                        ->one()
        ) {
            return [
                'post' => $post->toArray([], array_keys($post->extraFields())),
            ];
        }

        throw new NotFoundHttpException(__('Post not found'));
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionView()
    {
        /**
         * @var $post Post
         */
        $ios = [];
        $and = [];

        if ($post = $this->findPost()) {
            if ($push = $this->get('push')) {
                if ($push == 'android') {
                    $and = $post->sendPushNotificationAndroid(true);
                }
            }
            return [
                'push' => [
                    'ios' => $ios,
                    'and' => $and,
                ],
                'post' => $post->toArray([], array_keys($post->extraFields())),
            ];
        }
    }

    /**
     * @return Post|array|\yii\mongodb\ActiveRecord
     * @throws NotFoundHttpException
     */
    private function findPost()
    {
        if ($post = Post::find()
                        ->where([
                                    '_id'    => $this->get('id', time()),
                                    'status' => Post::STATUS_PUBLISHED
                                ])
                        ->one()
        ) {
            return $post;
        }

        throw new NotFoundHttpException(__('Post not found'));
    }

    /**
     * @param Post $post
     * @return bool
     * 1 1 4
     * 1 0 1
     * 0 0 2
     * 0 0 3
     * 0 0 4
     * 1 1 0
     * 0 0 1
     * 1 0 2
     * 1 0 3
     * 0 0 4
     * 0 0 5
     * 1 1 0
     */
    private function getPriority(Post $post)
    {
        if ($post->hasPriority()) {
            if (self::$noPriorityCounter >= 4) {
                self::$noPriorityCounter = 0;
                return true;
            }
        }

        self::$noPriorityCounter += 1;
        return false;
    }
}