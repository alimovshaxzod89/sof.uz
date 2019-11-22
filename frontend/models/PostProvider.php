<?php

namespace frontend\models;

use common\components\Config;
use common\models\Admin;
use common\models\Post;
use common\models\Tag;
use Imagine\Image\ManipulatorInterface;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Timestamp;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PostProvider extends Post
{
    public static function getTopAuthors($limit = 5)
    {
        return self::find()
                   ->active()
                   ->andWhere(['_author_post' => ['$gt' => -1]])
                   ->orderBy(['_author_post' => SORT_ASC])
                   ->limit($limit)
                   ->all();
    }

    public function metaCategoriesList()
    {
        $out = [];
        foreach ($this->categories as $category) {
            $out[] = Html::a($category->name, $category->getViewUrl(), ['data-pjax' => 0]);
        }

        return implode(', ', $out);
    }

    /**
     * @return PostQuery|object
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return \Yii::createObject(PostQuery::class, [get_called_class()]);
    }

    public function getImage($width = 532, $height = 356, $manipulation = ManipulatorInterface::THUMBNAIL_OUTBOUND, $watermark = false)
    {
        $watermark = $this->hasAttribute('img_watermark') && $this->img_watermark;
        return self::getCropImage($this->image, $width, $height, $manipulation, $watermark, 85);
    }

    /**
     * @param string $mark
     * @param int    $limit
     * @return array|Post|\yii\mongodb\ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public static function getByMark($mark = self::LABEL_REGULAR, $limit = 10, $exclude = [])
    {
        $result = self::find()
                      ->active()
                      ->orderBy(['published_on' => -1])
                      ->limit($limit);

        if (count($exclude)) {
            $result->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        return $result->all();
    }

    /**
     * @param string $mark
     * @param int    $limit
     * @return array|Post|\yii\mongodb\ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public static function getSidebarImportant($limit = 10, $exclude = [])
    {
        $result = self::find()
                      ->active()
                      ->orFilterWhere(['label' => self::LABEL_IMPORTANT])
                      ->orFilterWhere(['is_main' => true])
                      ->orderBy(['published_on' => -1])
                      ->limit($limit);

        if (count($exclude)) {
            $result->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        return $result->all();
    }


    /**
     * @param string $mark
     * @param int    $limit
     * @return array|Post|\yii\mongodb\ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTrendingNews($limit = 3)
    {
        $result = self::find()
                      ->active()
                      ->andFilterWhere(['is_main' => true])
                      ->orderBy(['views_l3d' => -1])
                      ->limit($limit);

        return $result->all();
    }


    /**
     * @param string $type
     * @param int    $limit
     * @return array|Post|\yii\mongodb\ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public static function getByType($type, $limit = 10)
    {
        $result = self::find()
                      ->active()
                      ->andWhere(['type' => $type])
                      ->orderBy(['published_on' => -1])
                      ->limit($limit)
                      ->all();
        return count($result) ? $result : [];
    }

    /**
     * @param int   $limit
     * @param bool  $batch
     * @param array $exclude
     * @return PostProvider[] | ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getLastPosts($limit = 10, $batch = false, $exclude = [])
    {
        $query = self::find()
                     ->active()
                     ->orderBy(['ad_time' => SORT_DESC, 'published_on' => SORT_DESC])
                     ->limit($limit);

        if (count($exclude)) {
            $query->andFilterWhere([
                                       '_id' => [
                                           '$nin' => array_values($exclude)
                                       ]
                                   ]);
        }

        if ($batch) {
            return new ActiveDataProvider([
                                              'query'      => $query,
                                              'pagination' => [
                                                  'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                              ],
                                          ]);
        }

        return $query->all();
    }


    /**
     * @param     $column
     * @param int $limit
     * @return array|Post|\yii\mongodb\ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public static function getBySort($column, $limit = 10)
    {
        $result = self::find()
                      ->active()
                      ->addOrderBy([$column => -1])
                      ->limit($limit)
                      ->all();
        return count($result) ? $result : [];
    }

    public static function getTopPosts($limit = 6, $exclude = [])
    {
        $query = self::find()->active();
        if (is_array($exclude) && count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        $gte    = new Timestamp(1, time() - 5 * 24 * 3600);
        $result = $query->andWhere([
                                       'published_on' => ['$gte' => $gte],
                                   ])
                        ->addOrderBy(['views_l3d' => -1])
                        ->limit($limit)
                        ->all();
        return \count($result) ? $result : [];
    }

    /**
     * @param int        $limit
     * @param ObjectId[] $exclude
     * @param bool       $provider
     * @return PostProvider[] | ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getPopularPosts($limit = 5, $exclude = [], $provider = false)
    {
        $date = new Timestamp(1, strtotime("-3 days"));

        $query = self::find()
                     ->active()
                     ->andFilterWhere([
                                          'views'        => ['$gte' => 1],
                                          'published_on' => ['$gte' => $date],
                                      ])
                     ->orderBy(['views' => SORT_DESC])
                     ->limit($limit);

        if (count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        if ($provider) {
            return new ActiveDataProvider([
                                              'query'      => $query,
                                              'pagination' => [
                                                  'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                              ],
                                          ]);
        }
        return $query->all();
    }

    /**
     * @param int $limit
     * @return PostProvider[]
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTopPost($limit = 6)
    {
        $result = self::find()
                      ->active()
                      ->andWhere(['is_main' => ['$eq' => true]])
                      ->orderBy(['published_on' => -1])
                      ->limit($limit)
                      ->all();
        return $result;
    }


    /**
     * @param CategoryProvider $category
     * @param int              $limit
     * @param array            $exclude
     * @param bool             $provider
     * @return PostProvider[]|boolean|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getCategoryHeadPosts(CategoryProvider $category, $limit = 2, $exclude = [], $provider = false)
    {
        $query = self::find()
                     ->active()
                     ->andWhere(
                         [
                             '_categories' => [
                                 '$elemMatch' => [
                                     '$eq' => $category->id,
                                 ],
                             ],
                         ]
                     )
                     ->orderBy(['published_on' => -1])
                     ->limit($limit);

        if (count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        if (!$provider) {
            return $query->all();
        }
        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    /**
     * @param int $limit
     * @return self|array|\yii\mongodb\ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTopVideos($limit = 2)
    {
        return static::find()
                     ->active()
                     ->andWhere(['has_video' => true])
                     ->orderBy(['published_on' => SORT_DESC])
                     ->limit($limit)
                     ->all();
    }

    /**
     * @param int $limit
     * @return self|array|\yii\mongodb\ActiveRecord
     * @throws \yii\base\InvalidConfigException
     */
    public static function getTopPhotos($limit = 4)
    {
        return static::find()
                     ->active()
                     ->andWhere(['has_gallery' => true])
                     ->orderBy(['published_on' => SORT_DESC])
                     ->limit($limit)
                     ->all();
    }

    public static function getRandomPosts($limit = 2)
    {
        return static::find()
                     ->active()
                     ->andWhere([
                                    '_author' => ['$nin' => [null, ""]],
                                ])
                     ->orderBy(['published_on' => -1])
                     ->limit($limit)
                     ->all();
    }

    public static function getFeed($limit)
    {
        $query = self::find()
                     ->active()
                     ->orderBy(['published_on' => -1]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    /**
     * @param array $exclude
     * @param       $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getLastVideos($exclude = [], $limit = 10)
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    'has_video' => ['$eq' => true],
                                ])
                     ->orderBy(['published_on' => -1]);

        if (\count($exclude)) {
            $query->andWhere(['_id' => ['$nin' => $exclude]]);
        }

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    /**
     * @param array $exclude
     * @param int   $limit
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getLastPhotos($exclude = [], $limit = 10)
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    'has_gallery' => ['$eq' => true],
                                ])
                     ->orderBy(['published_on' => -1]);

        if (\count($exclude)) {
            $query->andWhere(['_id' => ['$nin' => $exclude]]);
        }

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    public static function getPostsByQuery($string, $limit)
    {
        $query = self::find()
                     ->orderBy(['published_on' => -1]);

        $attrs = ['title', 'content'];
        foreach ($attrs as $attr) {
            $query->orFilterWhere([$attr => ['$regex' => $string, '$options' => 'si']]);
            $query->orFilterWhere(["_translations.{$attr}_uz" => ['$regex' => $string, '$options' => 'si']]);
            $query->orFilterWhere(["_translations.{$attr}_oz" => ['$regex' => $string, '$options' => 'si']]);
        }

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'totalCount' => 1000,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    public static function dataProvider($posts = [], $exclude = [], $limit = 10)
    {
        $postIds = [];
        if ($posts && count($posts) > 0)
            $postIds = array_map(function (Post $post) {
                return $post->_id;
            }, $posts);

        $query = self::find()
                     ->active()
                     ->andWhere(['_id' => ['$in' => $postIds]])
                     ->orderBy(['published_on' => -1]);

        if (\count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    public static function getLastNews()
    {
        if ($lastNewsCategory = CategoryProvider::findOne(['slug' => 'yangiliklar'])) {
            return self::getPostsByCategory($lastNewsCategory);
        };

        return [];
    }

    public static function getPostsByCategory(CategoryProvider $category, $limit = 10, $dataProvider = true, $exclude = [])
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    '_categories' => [
                                        '$elemMatch' => [
                                            '$in' => [$category->id, $category->_id],
                                        ],
                                    ],
                                ])
                     ->orderBy(['ad_time' => SORT_DESC, 'published_on' => SORT_DESC]);

        if (is_array($exclude) && count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        if ($dataProvider) {
            return new ActiveDataProvider([
                                              'query'      => $query,
                                              'pagination' => [
                                                  'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                              ],
                                          ]);
        }

        return $query->limit($limit)->all();
    }

    public static function getPostsByTag(TagProvider $tag, $limit = 10, $dataProvider = true, $exclude = [])
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    '_tags' => [
                                        '$elemMatch' => [
                                            '$in' => [$tag->_id, $tag->id],
                                        ],
                                    ],
                                ])
                     ->orderBy(['published_on' => -1]);

        if (\count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        if ($dataProvider) {
            return new ActiveDataProvider([
                                              'query'      => $query,
                                              'pagination' => [
                                                  'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                              ],
                                          ]);
        }

        return $query->limit($limit)->all();
    }

    public static function getPostsExcludeCategory(CategoryProvider $category, $limit = 10)
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    '_categories' => [
                                        '$elemMatch' => [
                                            '$ne' => $category->id,
                                        ],
                                    ],
                                ])
                     ->orderBy(['published_on' => -1]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    public static function getAuthorPosts(Admin $model, $limit = 10)
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    '_author' => $model->_id,
                                ])
                     ->orderBy(['published_on' => -1]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    public static function getTopics()
    {
        $posts = [];
        $tags  = Tag::find()->where(['is_topic' => ['$eq' => true]])->orderBy(['count_l5d' => -1])->all();
        if (count($tags)) {
            $posts = array_map(function (Tag $tag) {
                return $tag->posts;
            }, $tags);
        } else {
            $tags  = Tag::getTrending(100);
            $posts = array_map(function (Tag $tag) {
                return $tag->posts;
            }, $tags);
        }
        return $posts;
    }

    public function getHighlightedByTag($tag)
    {
        $tag = trim($tag);

        $result = [];

        $text = html_entity_decode(strip_tags($this->content));
        $len  = mb_strlen($text);

        $padding   = 70;
        $actualEnd = 0;

        for ($i = 0; $i < 2; $i++) {
            $pos = mb_stripos($text, $tag, $actualEnd);
            if ($pos > -1) {
                if ($pos - $padding >= 0) {
                    $start = $pos - $padding;
                    if ($pos + $padding <= $len) {
                        $end = $pos + $padding;
                    } else {
                        $end = $len;
                    }
                } else {
                    $start = 0;
                    if ($pos + 2 * $padding <= $len) {
                        $end = $pos + 2 * $padding;
                    } else {
                        $end = $len;
                    }
                }
                $newStart = mb_strpos($text, ' ', $start);
                if ($newStart > $pos) $newStart = $start;

                $end = mb_strpos($text, ' ', $end);

                $cutText = mb_substr($text, $newStart, $end - $newStart);

                $result[]  = $this->highlightKeywords($cutText, $tag);
                $actualEnd += $end;
            } else {
                break;
            }
        }
        return implode(' ', $result);
    }

    public function highlightKeywords($text, $keyword, $dots = true)
    {
        $keyword = (trim($keyword));
        $wrapped = "<span class='search_text'>$keyword</span>";
        $text    = preg_replace('/' . strtolower($keyword) . '/iu', $wrapped, $text);

        if ($dots)
            return $text ? $text . ' ...' : '';
        return $text;
    }
}