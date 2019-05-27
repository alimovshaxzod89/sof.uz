<?php

namespace console\controllers;

use common\components\Translator;
use common\models\Category;
use common\models\Post;
use common\models\Tag;
use console\models\Category as OldCat;
use console\models\Post as OldPost;
use console\models\Tag as OldTag;
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
                                         'scenario'      => 'insert',
                                         'name'          => "Бўлимлар",
                                         'slug'          => 'bolimlar',
                                         '_translations' => [
                                             'name_uz' => "Бўлимлар",
                                             'name_ru' => "Рубрики",
                                         ],
                                     ]);
                if ($root->save()) {
                    $this->stdout("Created `{$root->name}` root category successfully.\n", Console::FG_GREEN);
                } else {
                    $this->stderr("Cannot saved root category.\n", Console::FG_RED);
                    return false;
                }
            }

            Console::startProgress(0, count($categories), 'Start Convert Categories');
            foreach ($categories as $i => $category) {
                $cat = new Category([
                                        'scenario'      => 'insert',
                                        'name'          => $category->name ?: $category->ru,
                                        'old_id'        => $category->id,
                                        'slug'          => $category->url,
                                        '_translations' => [
                                            'name_uz' => $category->name ?: $category->ru,
                                            'name_ru' => $category->ru ?: $category->name,
                                        ],
                                        'parent'        => $root->getId()
                                    ]);
                if ($cat->save()) {
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
                                    'scenario' => 'insert',
                                    'name'     => $tag->text,
                                    'name_uz'  => $tag->text,
                                    'old_id'   => $tag->id,
                                    'slug'     => $tag->slug ?: $slug,
                                ]);
                if ($new->save()) {
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
        $postsQuery = OldPost::find()
                             ->select([
                                          'id',
                                          'title',
                                          'full',
                                          'views',
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
                $post->toMongo();
                Console::updateProgress($i + 1, count($posts));
                flush();
            }
            Console::endProgress();
            ob_get_clean();
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
}
