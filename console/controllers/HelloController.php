<?php

namespace console\controllers;

use common\components\Translator;
use common\models\Category;
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
        $categories = OldCat::find()->all();
        $root       = new Category([
                                       'scenario'      => 'insert',
                                       'name'          => "Бўлимлар",
                                       'slug'          => 'bolimlar',
                                       '_translations' => [
                                           'name_uz' => "Бўлимлар",
                                           'name_ru' => "Рубрики",
                                       ],
                                   ]);
        if ($root->save()) {
            //$this->stdout("Created `{$root->name}` category successfully.\n", Console::FG_GREEN);

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
        $tags = OldTag::find()->all();
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

    public function actionConvert()
    {
        $this->stdout("Converter Categories.\n", Console::FG_GREEN);
        $this->convertCategories();
        $this->stdout("Converter Tags.\n", Console::FG_GREEN);
        $this->convertTags();
        $this->stdout("Converter Posts.\n", Console::FG_GREEN);

        /* @var $posts OldPost[] */
        $posts = OldPost::find()->select(['id', 'title', 'full', 'views', 'category_id', 'date', 'img', 'status', 'slug'])->all();
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
