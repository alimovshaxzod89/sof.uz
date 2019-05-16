<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

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
                   ->where(['_domain' => Config::getDomain(), 'is_hidden' => ['$ne' => true]])
                   ->orderBy(['count_posts' => SORT_DESC])
                   ->limit($limit)
                   ->all();
    }

    /**
     * @return PostProvider[]
     * @throws \yii\base\InvalidConfigException
     */
    public function getPosts()
    {
        return PostProvider::find()
                           ->active()
                           ->andWhere(['_categories' => ['$elemMatch' => ['$in' => [$this->id]]]])
                           ->all();
    }

    public function getChildren()
    {
        return self::getChildCategories($this->id);
    }

    /**
     * @return Category[]
     */
    public static function getHomeCategories($limit = 2)
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
                foreach ($child->child as $child) {
                    if ($child->is_home) {
                        $cats[$child->home_order] = $child;
                    }
                }
            }
        }
        ksort($cats);

        return array_slice($cats,0,$limit);
    }

}
