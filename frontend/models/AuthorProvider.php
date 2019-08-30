<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

namespace frontend\models;

use common\models\Blogger;
use yii\data\ActiveDataProvider;

class AuthorProvider extends Blogger
{
    public function getLastPosts($limit = 10, $exclude = [])
    {
        $query = PostProvider::find()
                             ->where(['_author' => $this->getId()])
                             ->andWhere(['status' => self::STATUS_ENABLE, 'has_audio' => false, 'has_video' => false])
                             ->orderBy(['published_on' => SORT_DESC])
                             ->limit($limit);

        if (count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        $result = $query->all();
        return count($result) ? $result : [];
    }

    /**
     * @param $column
     * @param int $limit
     * @return ActiveDataProvider
     */
    public static function getBySort($column, $limit = 10)
    {
        $query = self::find()
                     ->where(['status' => self::STATUS_ENABLE])
                     ->addOrderBy([$column => SORT_DESC]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => $limit,
                                          ],
                                      ]);
    }

    /**
     * @param int $limit
     * @return self[]|array|\yii\mongodb\ActiveRecord
     */
    public static function getList($limit = 6)
    {
        return self::find()->orderBy(['posts' => SORT_DESC])->limit($limit)->all();
    }

    public static function dataProvider($authors = [], $exclude = [], $size = 10)
    {
        $authorIds = [];
        if (count($authors) > 0)
            $authorIds = array_map(function (Blogger $post) {
                return $post->_id;
            }, $authors);

        $query = self::find()
                     ->where(['_id' => ['$in' => $authorIds]])
                     ->andWhere(['status' => Blogger::STATUS_ENABLE])
                     ->orderBy(['created_at' => SORT_DESC]);

        if (count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => $size,
                                          ],
                                      ]);
    }
}