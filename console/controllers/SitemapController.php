<?php

namespace console\controllers;

use common\components\LocaleSitemap;
use common\models\Category;
use common\models\Post;
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

        /* @var $posts Post[] */
        $posts = Post::find()
                     ->where(['status' => Post::STATUS_PUBLISHED])
                     ->select(['slug', 'updated_at'])
                     ->orderBy(['published_on' => SORT_DESC])->all();
        foreach ($posts as $post) {
            $sitemap->addItem(
                $post->getViewUrl(null, false),
                $post->is_main ? 1 : 0.9,
                'daily',
                $post->updated_at->getTimestamp(),
                true
            );
        }

        $sitemap->createSitemapIndex($host, 'Today');
    }
}