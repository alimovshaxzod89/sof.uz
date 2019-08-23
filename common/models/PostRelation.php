<?php

namespace common\models;

use Yii;

/**
 * Class Comment
 * @property string     question
 * @property string     status
 * @property string     expire_time
 * @property string[]   answers
 * @property PollItem[] items
 * @property integer    votes
 * @package common\models
 */
class PostRelation extends MongoModel
{
    protected $_idAttributes = ['p1', 'p2'];

    const TYPE_SIMILAR = 's';
    const TYPE_RELATED = 'r';

    public static function collectionName()
    {
        return 'post_relation';
    }

    public function attributes()
    {
        return [
            '_id',
            'p1',
            'p2',
            'type',
            'created_at',
        ];
    }



    public static function buildRelation()
    {
        /**
         * @var $tag   Tag
         * @var $post1 Post
         * @var $post2 Post
         */
        $tags    = Tag::find()->where(['count' => ['$gt' => 1]])->all();
        $tagData = [];

        foreach ($tags as $tag) {
            $tagPosts = Post::find()
                            ->where(['_tags' => ['$elemMatch' => ['$in' => [$tags->getId()]]]])
                            ->all();
        }


        $date = new \DateTime();

        $key = [
            'type'  => self::TYPE_POST_VIEW,
            'model' => $post->_id,
            'hour'  => (int)$date->format('H'),
            'day'   => (int)$date->format('d'),
            'month' => (int)$date->format('m'),
            'year'  => (int)$date->format('Y'),
        ];

        if ($stat = self::find()->where($key)->one()) {
            return $stat->updateCounters(['count' => 1]);
        } else {
            $key['count'] = 1;
            $key['time']  = (int)$date->format('U');

            return boolval(self::getConnection()
                               ->getCollection(self::collectionName())
                               ->insert($key));
        }
    }

    public static function indexPostViewsAll()
    {
        echo "indexPostViewsAll===================\n";
        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate(
                          ['$match' => ['time' => ['$gt' => 0]]],
                          array('$group' => array(
                              '_id'   => '$model',
                              'count' => ['$sum' => '$count'],
                          ))
                      );

        foreach ($result as $item) {
            Post::updateAll(['views' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    public static function indexPostViewL3D()
    {
        echo "indexPostViewL3D===================\n";
        $date = new \DateTime();
        $time = (int)$date->format('U')
            - 3 * 24 * 3600
            - ((int)$date->format('h')) * 3600;

        $result = self::getConnection()
                      ->getCollection(self::collectionName())
                      ->aggregate(
                          ['$match' => ['time' => ['$gt' => $time]]],
                          ['$group' => [
                              '_id'   => '$model',
                              'count' => ['$sum' => '$count'],
                          ]]
                      );

        foreach ($result as $item) {
            Post::updateAll(['views_l3d' => $item['count']], ['_id' => $item['_id']]);
        }
    }

    /**
     * @return \yii\mongodb\Connection
     */
    private static function getConnection()
    {
        return Yii::$app->mongodb;
    }
}


