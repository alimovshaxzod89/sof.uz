<?php

namespace frontend\models;

use common\components\Config;
use common\models\Category;

/**
 * Class CategoryProvider
 * @package frontend\models
 * @property CategoryProvider[] $children
 */
class CategoryProvider extends Category
{
    /**
     * @param int $limit
     * @return Category[]
     */
    public static function getTrending($limit = 6)
    {
        return self::find()
                   ->where(['is_hidden' => ['$ne' => true]])
                   ->orderBy(['count_posts' => SORT_DESC])
                   ->limit($limit)
                   ->all();
    }

    public static function getFooterTopCategory()
    {
        $cat = Config::get(Config::CONFIG_FOOTER_TOP_POSTS);
        return CategoryProvider::findOne($cat);
    }

    /**
     * @param bool $limit
     * @return PostProvider[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getPosts($limit = false)
    {
        $query = PostProvider::find()
                             ->active()
                             ->andWhere([
                                            '_categories' => [
                                                '$elemMatch' => [
                                                    '$eq' => $this->_id
                                                ]
                                            ]
                                        ]);

        if ($limit)
            $query->limit($limit);

        return $query->all();
    }

    public function getChildren()
    {
        return self::getChildCategories($this->id);
    }

    /**
     * @param int $limit
     * @return \yii\data\ActiveDataProvider
     */
    public function getProvider($limit = 10)
    {
        return PostProvider::getPostsByCategory($this, $limit);
    }

    /**
     * @param int $limit
     * @return Category[]
     */
    public static function getHomeCategories($limit = 4)
    {
        $domainCats = self::getCategoryTree([], Config::getRootCatalog());

        $cats = [];
        foreach ($domainCats as $category) {
            if ($category->is_home) {
                $cats[$category->home_order] = $category;
            }
            foreach ($category->child as $child) {
                if ($child->is_home) {
                    $cats[$child->home_order] = $child;
                }
                foreach ($child->child as $ch) {
                    if ($ch->is_home) {
                        $cats[$ch->home_order] = $ch;
                    }
                }
            }
        }
        ksort($cats);

        return array_slice($cats, 0, $limit);
    }

}
