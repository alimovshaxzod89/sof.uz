<?php

namespace console\controllers;

use common\components\LocaleSitemap;
use common\models\Category;
use common\models\Post;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;
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
        $host = Yii::getAlias('@frontendUrl') . '/';
        $sitemap = new LocaleSitemap($host);
        $sitemap->setDomain($host);

        $sitemap->setPath(Yii::getAlias('@frontend/web') . DIRECTORY_SEPARATOR);

        $sitemap->addItem('', 1, 'hourly');

        /* @var $posts Post[] */
        $posts = Post::find()
            ->where(['status' => Post::STATUS_PUBLISHED])
            ->select(['slug', 'updated_at'])
            ->orderBy(['published_on' => SORT_DESC])->all();
        foreach ($posts as $post) {
            $sitemap->addItem(
                linkTo('post/' . $post->slug, true),
                1,
                'daily',
                $post->updated_at->getTimestamp(),
                true
            );
        }

        $sitemap->createSitemapIndex($host, 'Today');
    }
}