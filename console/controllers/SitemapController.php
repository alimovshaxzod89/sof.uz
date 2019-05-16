<?php
/**
 * Created by PhpStorm.
 * User: shavkat
 * Date: 2/1/17
 * Time: 4:54 PM
 */

namespace console\controllers;


use common\components\LocaleSitemap;
use common\models\Blogger;
use common\models\Category;
use common\models\Tag;
use console\models\Post;
use Yii;
use yii\console\Controller;

class SitemapController extends Controller
{
    public function actionIndex()
    {
        self::generate();
    }

    public static function generate()
    {
        /**
         * @var $post     Post
         * @var $blogger  Blogger
         * @var $category Category
         * @var $tag      Tag
         */
        $host    = Yii::getAlias('@frontendUrl') . '/';
        $sitemap = new LocaleSitemap($host);
        $sitemap->setDomain($host);

        $sitemap->setPath(Yii::getAlias('@frontend/web') . DIRECTORY_SEPARATOR);

        $sitemap->addItem('', 1, 'hourly');

        $menu = Category::getCategoryTree(['is_menu' => true], Category::findOne(['slug' => 'categories'])->id);

        $time = (new \DateTime())->getTimestamp();
        foreach ($menu as $item) {
            $sitemap->addItem(
                $item->getViewUrl([]),
                1,
                'daily',
                $time,
                true
            );
        }

        foreach (Post::find()
                     ->where(['status' => Post::STATUS_PUBLISHED])
                     ->select(['url', 'updated_at'])
                     ->orderBy(['published_on' => SORT_DESC])
                     ->all() as $post) {


            $sitemap->addItem(
                $post->getViewUrl(null, false),
                $post->hasPriority() ? 1 : 0.9,
                'daily',
                $post->updated_at->getTimestamp(),
                true
            );
        }

        $sitemap->createSitemapIndex($host, 'Today');
    }
}