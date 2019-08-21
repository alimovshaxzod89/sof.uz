<?php

namespace frontend\models;

use common\components\Config;
use common\models\Blogger;
use common\models\Post;
use common\models\Tag;
use Imagine\Image\ManipulatorInterface;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Timestamp;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;

class PostProvider extends Post
{
    public function hasCategory()
    {
        return $this->category instanceof CategoryProvider;
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
                      ->orderBy(['published_on' => SORT_DESC])
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
                      ->orderBy(['published_on' => SORT_DESC])
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
                      ->orderBy(['views_l3d' => SORT_DESC])
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
                      ->orderBy(['published_on' => SORT_DESC])
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
                     ->orderBy(['published_on' => SORT_DESC])
                     ->limit($limit);

        if (count($exclude)) {
            $query->andFilterWhere(['_id' => ['$nin' => array_values($exclude)]]);
        }

        if ($batch) {
            return new ActiveDataProvider([
                                              'query'      => $query,
                                              'pagination' => [
                                                  'pageSize' => $limit,
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
                      ->addOrderBy([$column => SORT_DESC])
                      ->limit($limit)
                      ->all();
        return count($result) ? $result : [];
    }

    public static function getTopPosts($limit = 10)
    {
        $result = self::find()
                      ->active()
                      ->andWhere([
                                     'published_on' => ['$gte' => new Timestamp(1, time() - 5 * 24 * 3600)],
                                 ])
                      ->addOrderBy(['views_l3d' => SORT_DESC])
                      ->limit($limit)
                      ->all();
        return \count($result) ? $result : [];
    }

    /**
     * @param int        $limit
     * @param ObjectId[] $exclude
     * @return PostProvider[] | ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getPopularPosts($limit = 10, $exclude = [], $provider = false)
    {
        $query = self::find()
                     ->active()
                     ->andFilterWhere(['views' => ['$gte' => 1]])
                     ->orderBy(['views_l3d' => SORT_DESC])
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
    public static function getTopPost($limit = 5)
    {
        $result = self::find()
                      ->active()
                      ->andWhere([
                                     'is_main' => ['$eq' => true],
                                 ])
                      ->addOrderBy(['published_on' => SORT_DESC])
                      ->limit($limit)
                      ->all();
        return $result;
    }


    /**
     * @param Category $category
     * @param int      $limit
     * @param array    $exclude
     * @param bool     $provider
     * @return PostProvider[]|boolean|ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public static function getCategoryHeadPosts(Category $category, $limit = 2, $exclude = [], $provider = false)
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
                     ->orderBy(['published_on' => SORT_DESC])
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
     */
    public static function getTopPhotos($limit = 2)
    {
        return static::find()
                     ->active()
                     ->andWhere(['has_gallery' => true])
                     ->orderBy(['label' => SORT_ASC, 'published_on' => SORT_DESC])
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
                     ->orderBy(['published_on' => SORT_DESC])
                     ->limit($limit)
                     ->all();
    }

    public static function getFeed($limit)
    {
        $query = self::find()
                     ->active()
                     ->orderBy(['published_on' => SORT_DESC]);

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
                     ->orderBy(['published_on' => SORT_DESC]);

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
     * @param       $limit
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
                     ->orderBy(['published_on' => SORT_DESC]);

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
        $title = Config::getLanguageCode();

        $query = self::find()
                     ->orderBy(['published_on' => SORT_DESC]);


        $query->orFilterWhere([
                                  "_translations.title_uz" => ['$regex' => $string, '$options' => 'si'],
                              ]);

        $query->orFilterWhere([
                                  "_translations.title_oz" => ['$regex' => $string, '$options' => 'si'],
                              ]);

        $query->orFilterWhere([
                                  "_translations.content_$title" => ['$regex' => $string, '$options' => 'si'],
                              ]);

        $query->active(["_translations.content_$title"]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }

    public static function getPostsByTag(Tag $tag, $limit)
    {
        $title = Config::getLanguageCode();

        $query = self::find()
                     ->active(["_translations.content_$title"])
                     ->andWhere([
                                    '_tags' => [
                                        '$elemMatch' => [
                                            '$in' => [$tag->_id, $tag->id],
                                        ],
                                    ],
                                ])
                     ->orderBy(['published_on' => SORT_DESC]);

        return new ActiveDataProvider([
                                          'query'      => $query,
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
                     ->orderBy(['published_on' => SORT_DESC]);

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
        if ($lastNewsCategory = Category::findOne(['slug' => 'yangiliklar'])) {
            return self::getPostsByCategory($lastNewsCategory);
        };

        return [];
    }

    public static function getPostsByCategory(Category $category, $limit = 10, $exclude = [])
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    '_categories' => [
                                        '$elemMatch' => [
                                            '$eq' => $category->id,
                                        ],
                                    ],
                                ])
                     ->orderBy(['published_on' => SORT_DESC]);

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

    public static function getPostsExcludeCategory(Category $category, $limit = 10)
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
                     ->orderBy(['published_on' => SORT_DESC]);

        return new ActiveDataProvider([
                                          'query'      => $query,
                                          'pagination' => [
                                              'pageSize' => intval(\Yii::$app->request->get('load', $limit)),
                                          ],
                                      ]);
    }


    public static function getMinbarPosts($limit = 10, $exclude = [])
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    '_categories' => [
                                        '$elemMatch' => [
                                            '$in' => Category::$MINBAR,
                                        ],
                                    ],
                                ])
                     ->orderBy(['published_on' => SORT_DESC]);

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

    public static function getAuthorPosts(Blogger $model, $limit = 10)
    {
        $query = self::find()
                     ->active()
                     ->andWhere([
                                    '_author' => $model->id,
                                ])
                     ->orderBy(['published_on' => SORT_DESC]);

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
        $tags  = Tag::find()->where(['is_topic' => ['$eq' => true]])->orderBy(['count_l5d' => SORT_DESC])->all();
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

    public function getAuthorPostUrl()
    {
        return linkTo(['author/post', 'login' => $this->author->login, 'slug' => $this->url], true);
    }
}