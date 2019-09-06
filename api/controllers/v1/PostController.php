<?php

namespace api\controllers\v1;

use api\models\v1\Category;
use api\models\v1\Post;
use common\components\Config;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\web\NotFoundHttpException;

class PostController extends ApiController
{
    private static $noPriorityCounter = 4;

    public function behaviors()
    {
        $data = parent::behaviors();
        if (YII_DEBUG) return $data;

        return array_merge($data, [
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
                                ]
        );
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

        foreach (Config::getLanguageCodes() as $code) {
            $result->orFilterWhere(["_translations.title_" . $code => ['$regex' => $query, '$options' => 'si']]);
            $result->orFilterWhere(["_translations.content_" . $code => ['$regex' => $query, '$options' => 'si']]);
            $result->andFilterWhere(['status' => Post::STATUS_PUBLISHED, 'is_mobile' => true]);
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


    public function actionHome()
    {
        $exclude  = [];
        $mainPost = Post::find()
                        ->where([
                                    'status'    => Post::STATUS_PUBLISHED,
                                    'is_mobile' => true,
                                    'is_main'   => true,
                                ])
                        ->addOrderBy(['published_on' => SORT_DESC])
                        ->one();

        $exclude[] = $mainPost->_id;

        $lastNewsC = Category::findOne(['slug' => 'yangiliklar']);
        $lastNews  = Post::find()
                         ->andWhere([
                                        '_categories' => [
                                            '$elemMatch' => [
                                                '$eq' => $lastNewsC->id,
                                            ],
                                        ],
                                        '_id'         => ['$nin' => array_values($exclude)],
                                    ])
                         ->orderBy(['published_on' => SORT_DESC])
                         ->limit(10)
                         ->all();

        foreach ($lastNews as $news) $exclude[] = $news->_id;

        $topPosts = Post::find()
                        ->where([
                                    'status'    => Post::STATUS_PUBLISHED,
                                    'is_mobile' => true,
                                ])
                        ->addOrderBy(['views_l3d' => SORT_DESC])
                        ->limit(6)
                        ->all();


        $lastPosts = Post::find()
                         ->where([
                                     'status'      => Post::STATUS_PUBLISHED,
                                     'is_mobile'   => true,
                                 ])
                         ->orderBy(['published_on' => SORT_DESC])
                         ->limit(15);

        $lastPosts = $lastPosts->all();

        /**
         * @var $post Post
         * @var $mainPost Post
         */
        $fields = (new Post())->extraFields();
        unset($fields['similar']);
        unset($fields['similarTitle']);
        unset($fields['tags']);
        $fields = array_keys($fields);

        $mainPost = $mainPost->toArray([], $fields);

        $result = [];
        foreach ($lastNews as $post) {
            $result[] = $post->toArray([], $fields);
        }
        $lastNews = $result;

        $result = [];
        foreach ($topPosts as $post) {
            $result[] = $post->toArray([], $fields);
        }
        $topPosts = $result;

        $result = [];
        foreach ($lastPosts as $post) {
            $result[] = $post->toArray([], $fields);
        }
        $lastPosts = $result;

        return [
            'main'      => $mainPost,
            'important' => $lastNews,
            'top'       => $topPosts,
            'last'      => $lastPosts,
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
                                 'status'    => Post::STATUS_PUBLISHED,
                                 'is_mobile' => true,
                             ])
                     ->limit($limit)
                     ->orderBy([$order => SORT_DESC])
                     ->offset($page * $limit);

        if ($category) {
            if ($category = Category::findOne($category)) {
                $posts->andWhere(
                    [
                        '_categories' => [
                            '$elemMatch' => [
                                '$eq' => $category->getId(),
                            ],
                        ],
                    ]
                );
            } else {
                $category = null;
            }
        }

        if ($tag) {
            $posts->andWhere(
                [
                    '_tags' => [
                        '$elemMatch' => [
                            '$eq' => new ObjectId($tag),
                        ],
                    ],
                ]
            );
        }

        if ($after && strlen($after) == 10) {
            $posts->andWhere(['$gt', 'published_on', new Timestamp(1, intval($after) + 1)]);
        }

        if ($before && strlen($before) == 10) {
            $posts->andWhere(['$lt', 'published_on', new Timestamp(1, intval($before) - 1)]);
        }


        $result = [];
        if ($full) {
            $fields = (new Post())->extraFields();
            unset($fields['similar']);
            unset($fields['similarTitle']);
            unset($fields['tags']);

            $fields = array_keys($fields);

            foreach ($posts->all() as $post) {
                $item                 = $post->toArray([], $fields, false);
                $item['has_priority'] = $this->getPriority($post);
                $result[]             = $item;
            }
        } else {
            foreach ($posts->all() as $post) {
                $item                 = $post->toArray([]);
                $item['has_priority'] = $this->getPriority($post);
                $result[]             = $item;
            }
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


    public function actionViewUrl($slug)
    {
        if ($post = Post::find()
                        ->orFilterWhere(['short_id' => $slug])
                        ->orFilterWhere(['url' => $slug])
                        ->andFilterWhere(['status' => Post::STATUS_PUBLISHED])
                        ->one()
        ) {
            return [
                'post' => $post->toArray([], array_keys($post->extraFields())),
            ];
        }

        throw new NotFoundHttpException(__('Post not found'));
    }

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
     * @return Post
     * @throws NotFoundHttpException
     */
    private function findPost()
    {
        if ($post = Post::find()
                        ->where(['_id' => $this->get('id', time()), 'status' => Post::STATUS_PUBLISHED])
                        ->one()
        ) {
            return $post;
        }

        throw new NotFoundHttpException(__('Post not found'));
    }
}