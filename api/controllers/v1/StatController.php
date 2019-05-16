<?php

namespace api\controllers\v1;

use api\models\v1\Ad;
use api\models\v1\Post;
use common\models\Stat;
use common\models\Tag;
use MongoDB\BSON\ObjectId;
use Yii;
use yii\web\Response;

class StatController extends BaseController
{
    public function beforeAction($action)
    {
        Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
        return parent::beforeAction($action);
    }

    public function actionPost($id)
    {
        if ($post = $this->findPostModel($id)) {
            return Stat::registerPostView($post);
        }
    }

    public function actionClick($id)
    {
        if ($post = $this->findAdModel($id)) {
            return Stat::registerAdClick($post);
        }
    }

    public function actionTags($q = false)
    {
        /**
         * @var $tag Tag
         */
        Yii::$app->response->format = Response::FORMAT_JSON;

        $tags = Tag::find()
                   ->select(['name_uz', 'name_cy', 'name_ru', 'count', 'slug'])
                   ->orderBy(['count' => SORT_DESC])
                   ->limit(20);

        if ($q) {
            $tags->orFilterWhere(['name_uz' => ['$regex' => $q, '$options' => 'si']]);
            $tags->orFilterWhere(['name_ru' => ['$regex' => $q, '$options' => 'si']]);
            $tags->orFilterWhere(['name_cy' => ['$regex' => $q, '$options' => 'si']]);
        }
        $tags->andFilterWhere(['count' => ['$gt' => 0]]);

        $result = [];
        foreach ($tags->all() as $tag) {
            $result[] = [
                'l' => $tag->name,
                'c' => $tag->count,
                'u' => $tag->slug,
            ];
        }
        return $result;
    }

    /**
     * @param $id
     * @return Post
     */
    private function findPostModel($id)
    {
        if ($post = Post::find()
                        ->where([
                            'status' => Post::STATUS_PUBLISHED,
                            '_id'    => new ObjectId($id),
                        ])
                        ->one()
        ) {
            return $post;
        }

        return false;
    }

    /**
     * @param $id
     * @return Ad
     */
    private function findAdModel($id)
    {
        if ($ad = Ad::find()
                        ->where([
                            'status' => Ad::STATUS_ENABLE,
                            '_id'    => new ObjectId($id),
                        ])
                        ->one()
        ) {
            return $ad;
        }

        return false;
    }


    public function actionAudio($id)
    {
        /**
         * @var $post Post
         */
        if ($post = $this->findPostModel($id)) {
            if ($file = $post->getFilePath('audio')) {
                $info = pathinfo($file);
                return Yii::$app->response->sendFile($file, substr($post->url, 0, 20) . '-xabar.uz.' . $info['extension']);
            }
        }
    }

}