<?php

namespace console\controllers;

use common\components\Config;
use common\components\Translator;
use common\models\Admin;
use common\models\Category;
use common\models\Post;
use common\models\Tag;
use console\models\Category as OldCat;
use console\models\Post as OldPost;
use console\models\Tag as OldTag;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Timestamp;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * This command working with database
 * @since 2.0
 */
class HelloController extends Controller
{
    public function convertCategories()
    {
        /* @var $categories OldCat[] */
        $categoriesQuery = OldCat::find();
        $oldIds          = Category::find()->select(['old_id'])->asArray()->all();
        if (count($oldIds)) {
            $ids = array_column($oldIds, 'old_id');
            $categoriesQuery->where(['not in', 'id', $ids]);
        }
        $categories = $categoriesQuery->all();
        if (count($categories)) {
            $root = Category::find()->where(['slug' => 'bolimlar'])->one();
            if (!($root instanceof Category)) {
                $root = new Category([
                                         'scenario' => Category::SCENARIO_INSERT,
                                         'name'     => "Бўлимлар",
                                         'slug'     => 'bolimlar',
                                     ]);
                if ($root->save()) {
                    $root->syncLatinCyrill(Config::LANGUAGE_UZBEK, 1);
                    $this->stdout("Created `{$root->name}` root category successfully.\n", Console::FG_GREEN);
                } else {
                    $this->stderr("Cannot saved root category.\n", Console::FG_RED);
                    return false;
                }
            }

            Console::startProgress(0, count($categories), 'Start Convert Categories');
            foreach ($categories as $i => $category) {
                $cat = new Category([
                                        'scenario' => Category::SCENARIO_INSERT,
                                        'name'     => $category->name ?: $category->ru,
                                        'old_id'   => $category->id,
                                        'slug'     => $category->url,
                                        'parent'   => $root->getId()
                                    ]);
                if ($cat->save()) {
                    $cat->syncLatinCyrill(Config::LANGUAGE_UZBEK, 1);
                    //$this->stdout("-- Created `{$cat->name}` category successfully.\n", Console::FG_GREEN);
                }

                Console::updateProgress($i + 1, count($categories));
                flush();
            }
            Console::endProgress();
            ob_get_clean();
        }
    }

    public function convertTags()
    {
        /* @var $tags OldTag[] */
        $tagsQuery = OldTag::find();
        $oldIds    = Tag::find()->select(['old_id'])->asArray()->all();
        if (count($oldIds)) {
            $ids = array_column($oldIds, 'old_id');
            $tagsQuery->where(['not in', 'id', $ids]);
        }
        $tags = $tagsQuery->all();
        if (count($tags)) {
            Console::startProgress(0, count($tags), 'Start Convert Tags');
            foreach ($tags as $i => $tag) {
                $slug = Translator::getInstance()->translateToLatin($tag->text);
                $new  = new Tag([
                                    'scenario' => Tag::SCENARIO_INSERT,
                                    'name'     => $tag->text,
                                    'old_id'   => $tag->id,
                                    'slug'     => $tag->slug ?: $slug,
                                ]);
                if ($new->save()) {
                    $new->syncLatinCyrill(Config::LANGUAGE_UZBEK, 1);
                    //$this->stdout("Created `{$new->name}` tag successfully.\n", Console::FG_GREEN);
                }

                Console::updateProgress($i + 1, count($tags));
                flush();
            }
            Console::endProgress();
            ob_get_clean();
        }
    }

    public function convertPosts()
    {
        /* @var $posts OldPost[] */
        ini_set('memory_limit', '1G');
        $author = Admin::find()->orderBy(['created_at' => SORT_DESC])->one();
        if ($author instanceof Admin) {
            $postsQuery = OldPost::find()
                                 ->select([
                                              'id',
                                              'title',
                                              'full',
                                              'views',
                                              'photo',
                                              'category_id',
                                              'date',
                                              'img',
                                              'status',
                                              'slug'
                                          ]);
            $oldIds     = Post::find()->select(['old_id'])->asArray()->all();
            if (count($oldIds)) {
                $ids = array_column($oldIds, 'old_id');
                $postsQuery->where(['not in', 'id', $ids]);
            }
            $posts = $postsQuery->all();
            if (count($posts)) {
                Console::startProgress(0, count($posts), 'Start Convert Posts');
                foreach ($posts as $i => $post) {
                    $post->toMongo($author->_id);
                    Console::updateProgress($i + 1, count($posts));
                    flush();
                }
                Console::endProgress();
                ob_get_clean();
            }
        }
    }

    public function actionConvert()
    {
        $this->stdout("Converter Categories.\n", Console::FG_GREEN);
        $this->convertCategories();
        $this->stdout("Converter Tags.\n", Console::FG_GREEN);
        $this->convertTags();
        $this->stdout("Converter Posts.\n", Console::FG_GREEN);
        $this->convertPosts();
    }

    public function actionDate()
    {
        /* @var $posts Post[] */
        $posts = Post::find()->select(['old_id', 'published_on'])->all();
        Console::startProgress(0, count($posts), 'Start Convert Posts');
        foreach ($posts as $i => $post) {
            $old = \console\models\Post::findOne(['id' => $post->old_id]);
            if ($old instanceof \console\models\Post) {
                $post->updateAttributes(['published_on' => new Timestamp(1, $old->date)]);
                Console::updateProgress($i + 1, count($posts));
                flush();
            }
        }
        Console::endProgress();
        ob_get_clean();
    }

    public function actionAuthor()
    {
        Post::updateAll(['_author' => null]);
    }

    public function actionContent()
    {
        /* @var $posts Post[] */
        Post::getCollection()->createIndex(['content' => 'text']);
        $posts = Post::find()->select(['content'])->all();
        Console::startProgress(0, count($posts), 'Start Convert Posts');
        foreach ($posts as $i => $post) {
            Console::updateProgress($i + 1, count($posts));
            $content = \console\models\Post::clearContent($post->content);
            $post->updateAttributes(['content' => $content]);
            flush();
        }
        Console::endProgress();
        ob_get_clean();
    }

    public function actionTag()
    {
        /* @var $posts Post[] */
        $posts = Post::find()->select(['_tags'])->all();
        Console::startProgress(0, count($posts), 'Start Convert Posts');
        foreach ($posts as $i => $post) {
            Console::updateProgress($i + 1, count($posts));
            if (is_array($post->_tags) && count($post->_tags)) {
                $tags = array_filter(array_map(function ($tag) {
                    return $tag instanceof ObjectId ? $tag : false;
                }, $post->_tags));
                $post->updateAttributes(['_tags' => $tags]);
            }

            flush();
        }
        Console::endProgress();
        ob_get_clean();
    }

    public function actionFast()
    {
        /* @var $posts Post[] */
        ini_set('memory_limit', '-1');
        $posts = Post::find()->select(['image', 'content'])->all();
        Console::startProgress(0, count($posts), 'Start Convert Posts');
        foreach ($posts as $i => $post) {
            Console::updateProgress($i + 1, count($posts));
            $image   = $post->image;
            $baseUrl = \Yii::getAlias('@staticUrl');
            $content = str_replace('http://static.dushanba.uz', $baseUrl, $post->content);
            if (is_array($image) && isset($image['base_url'])) {
                $image['base_url'] = $baseUrl;
            }

            $post->updateAttributes(['content' => $content, 'image' => $image]);
            flush();
        }
        Console::endProgress();
        ob_get_clean();
    }
}
