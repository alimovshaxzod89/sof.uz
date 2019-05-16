<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace console\controllers;

use common\components\Config;
use common\components\Translator;
use common\models\Category;
use common\models\old\OldCategory;
use common\models\old\OldPage;
use common\models\old\OldPost;
use common\models\Post;
use common\models\Stat;
use console\models\Page;
use GuzzleHttp\Client;
use MongoDB\BSON\Timestamp;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\HtmlNode;
use Yii;
use yii\console\Controller;
use yii\db\Exception;

/**
 * This command working with database
 * @since 2.0
 */
class DbController extends Controller
{

    public function actionCatSave()
    {
        foreach (Category::find()->all() as $category) {
            echo $category->save();
        }

        Category::indexPostCount();
    }

    public function actionCat()
    {
        /**
         * @var OldCategory $cat
         */
        Yii::$app->language = Config::LANGUAGE_CYRILLIC;
        $translator         = new Translator();

        $cats = OldCategory::find()->all();

        foreach ($cats as $cat) {
            echo $cat->title . PHP_EOL;
            $category = Category::findOne(['old_id' => $cat->id]);

            if ($category == null) {
                $category = new Category();
            }

            $category->name   = $cat->title;
            $category->old_id = $cat->id;
            if ($cat->lan == 0) {
                $category->parent = Config::get(Config::CONFIG_CATALOG_POST);
                $category->setTranslation('name', $translator->translateToLatin($category->name), Config::LANGUAGE_UZBEK);
                $category->setTranslation('name', $translator->translateToCyrillic($category->name), Config::LANGUAGE_CYRILLIC);
            } else {
                $category->parent = Config::get(Config::CONFIG_CATALOG_SAMVES);
            }

            if ($category->save()) {
                echo "OK\n";
            }
        }
    }

    public function actionPostTitle()
    {

        Yii::$app->language = Config::LANGUAGE_CYRILLIC;
        $handle             = fopen(Yii::getAlias('@runtime/post.csv'), 'w+');

        foreach (Post::find()->orderBy(['old_id' => SORT_DESC])->all() as $post) {
            fputcsv($handle, ['id' => $post->old_id, 'title' => $post->title]);
        }
        fclose($handle);
    }


    public function actionUpdatePostTitle()
    {

        /**
         * @var $post Post
         */
        Yii::$app->language = Config::LANGUAGE_CYRILLIC;

        $handle     = fopen(Yii::getAlias("@runtime/post-new-1.csv"), 'r');
        $translator = new Translator();

        while ($row = fgetcsv($handle)) {
            if (isset($row[0]) && intval($row[0])) {
                if ($post = Post::find()->where(['old_id' => intval($row[0])])->one()) {
                    if ($post->has_russian) {
                        Yii::$app->language = Config::LANGUAGE_RUSSIAN;
                        $post->title        = $row[1];
                    } else {
                        Yii::$app->language = Config::LANGUAGE_CYRILLIC;
                        $post->title        = $row[1];
                        $post->setTranslation('title', $translator->translateToLatin($row[1]), Config::LANGUAGE_UZBEK);
                        if ($post->save()) {
                            echo $post->title . PHP_EOL;
                        } else {
                            print_r($post->getErrors());
                            die;
                        }
                    }
                }
            }

        }

        fclose($handle);
    }

    function mb_ucfirst($string)
    {
        $string = str_replace("\r\n", " ", $string);
        $string = str_replace("\n\r", " ", $string);
        $string = str_replace("\r", " ", $string);
        $string = str_replace("\n", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = trim(str_replace("  ", " ", $string));

        $upperAll   = false;
        $translator = new Translator();
        $hook       = $translator->translateToLatin($string);
        $string1    = preg_replace('/[^A-Z]+/', '', $hook);
        $string2    = preg_replace('/[^a-z]+/', '', $hook);

        if (mb_strlen($string1) > mb_strlen($string2)) {
            return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
        } else {
            return $string;
        }
    }

    public function actionPostView($id = false)
    {

        /**
         * @var OldPost $post
         */

        $posts = OldPost::find()->orderBy(['id' => SORT_DESC]);
        if ($id) {
            $posts->where(['id' => $id]);
        }
        foreach ($posts->all() as $post) {
            if ($p = Post::find()->where(['old_id' => $post->id])->one()) {
                if (Stat::registerPostView($p, intval($post->viewed))) {
                    echo $post->viewed . " - " . $p->title . PHP_EOL;
                };
            }
        }
    }

    public function actionPostContent($id = false)
    {
        $translator = new Translator();
        Yii::$app->language = Config::LANGUAGE_CYRILLIC;
        $posts = OldPost::find()->orderBy(['id' => SORT_DESC]);
        if ($id) {
            $posts->where(['id' => $id]);
        }

        foreach ($posts->all() as $post) {
            if ($post->lan) {
                Yii::$app->language = Config::LANGUAGE_RUSSIAN;
            } else {
                Yii::$app->language = Config::LANGUAGE_CYRILLIC;
            }


            if ($p = Post::findOne(['old_id' => $post->id])) {
                $p->info    = mb_substr(strip_tags($post->description), 0, 500);
                $p->content = $this->convertContent($post->text, $p->has_russian);

                if ($p->updatePost(true)) {
                    if ($post->lan) {
                        $p->has_russian = true;
                    } else {
                        $p->setTranslation('content', $translator->translateToLatin($p->content), 'uz');
                        $p->setTranslation('info', $translator->translateToLatin($p->info), 'uz');
                        $p->updateAttributes(['_translations' => $p->_translations]);
                    }
                    echo "OK\n";
                }

                echo $p->title . PHP_EOL;
            }
        }
    }

    public function actionPost($id = false)
    {


        /**
         * @var OldPost $post
         */

        Yii::$app->language = Config::LANGUAGE_CYRILLIC;
        $translator         = new Translator();

        $posts = OldPost::find()->orderBy(['id' => SORT_DESC]);
        if ($id) {
            $posts->where(['id' => $id]);
        }
        foreach ($posts->all() as $post) {
            if ($post->lan) {
                Yii::$app->language = Config::LANGUAGE_RUSSIAN;
            } else {
                Yii::$app->language = Config::LANGUAGE_CYRILLIC;
            }


            if ($p = Post::findOne(['old_id' => $post->id])) {

                if ($p == null) {
                    $p = new Post();
                }
                $p->old_id = $post->id;

                $p->info    = mb_substr(strip_tags($post->description), 0, 500);
                $p->is_main = boolval($post->dolzorab);
                $p->views   = $post->viewed;
                $p->status  = Post::STATUS_PUBLISHED;
                $p->type    = Post::TYPE_NEWS;

                if ($date = date_create_from_format("Y-m-d H:i:s", $post->date)) {
                    $p->published_on = new Timestamp(1, $date->getTimestamp());
                    $p->created_at   = new Timestamp(1, $date->getTimestamp());
                    $p->updated_at   = new Timestamp(1, $date->getTimestamp());
                } else {
                    print_r($post->getAttributes());
                    throw new Exception("Invalid date");
                }

                if ($c = Category::findOne(['old_id' => $post->category_id])) {
                    $p->_categories = [$c->id];
                }


                if ($post->lan) {
                    $p->has_russian = true;
                } else {
                    $p->has_uzbek = true;
                }

                $p->content = $this->convertContent($post->text, $p->has_russian);

                if ($image = $post->poster_w) {
                    $file     = ($p->has_russian ? 'http://sv.zarnews.uz/' : 'https://zarnews.uz') . $image;
                    $ext      = explode('.', $file);
                    $name     = md5($file) . '.' . $ext[count($ext) - 1];
                    $new_file = Yii::getAlias('@static/uploads/1/') . $name;
                    $image    = [
                        'path'     => '1/' . $name,
                        'name'     => time(),
                        'type'     => 'image/jpeg',
                        'order'    => 0,
                        'size'     => 1,
                        'base_url' => Yii::getAlias('@staticUrl/uploads'),
                        'caption'  => ['uz' => '', 'cy' => '', 'ru' => ''],
                    ];

                    if ($local = $this->getFile($file, $new_file)) {
                        $p->image = $image;
                        echo "+++++" . $new_file . PHP_EOL;
                    } else {
                        $p->image = null;
                        /*print_r($post->getAttributes());
                        throw new Exception('Image not found');*/
                    }
                }

                if ($p->updatePost(true)) {

                    if ($post->lan) {
                        $p->has_russian = true;
                    } else {
                        //$p->setTranslation('title', $translator->translateToLatin($p->title), 'uz');
                        $p->setTranslation('content', $translator->translateToLatin($p->content), 'uz');
                        $p->setTranslation('info', $translator->translateToLatin($p->info), 'uz');
                        $p->updateAttributes(['_translations' => $p->_translations]);
                    }

                    echo "OK\n";
                } else {
                    $errors = $p->getErrors();
                    if (isset($errors['url'])) {
                        $p->url = $p->url . '-' . time();
                        $p->updatePost(true);

                        if ($post->lan) {
                            $p->has_russian = true;
                        } else {
                            //$p->setTranslation('title', $translator->translateToLatin($p->title), 'uz');
                            $p->setTranslation('content', $translator->translateToLatin($p->content), 'uz');
                            $p->setTranslation('info', $translator->translateToLatin($p->info), 'uz');
                            $p->updateAttributes(['_translations' => $p->_translations]);
                        }
                    }
                }

                echo $p->title . PHP_EOL;
            }
        }
    }


    static $img        = [];
    static $firstImage = null;

    public function convertContent($content, $isRussian)
    {
        /**
         * @var $img HtmlNode[]
         * @var $ps HtmlNode[]
         */
        $dom = new Dom();
        $dom->loadStr($content, []);
        if ($ps = $dom->find('p')) {
            if (isset($ps[0])) {
                if ($img = $ps[0]->find('img')) {
                    if (isset($img[0])) {
                        if ($img[0]->tag->name() == 'img') {
                            $ps[0]->delete();
                        }
                    }
                }
            }
        }

        $content = preg_replace_callback('#<img.+?src="([^"]*)".*?/?>#i', function ($m) {
            //DbController::$img[] = $m[1];
            $new  = false;
            $file = false;
            if (strpos($m[1], '/admin') === 0) {
                $file = 'https://zarnews.uz/backend/web/img/' . str_replace('/admin/img/', '', $m[1]);
            } elseif (strpos($m[1], 'http://zar') === 0) {
                $file = str_replace('http:', 'https:', $m[1]);
            } elseif (strpos($m[1], 'http') === 0 && strpos($m[1], '?') === -1) {
                $file = $m[1];
            }

            if ($file) {
                $ext   = explode('.', $file);
                $name  = md5($file) . '.' . $ext[count($ext) - 1];
                $local = Yii::getAlias('@static/uploads/1/') . $name;

                if (file_exists($local)) {
                    $new = Yii::getAlias('@staticUrl/uploads/1/') . $name;
                } else if ($url = $this->getFile($file, $local)) {
                    $new = Yii::getAlias('@staticUrl/uploads/1/') . $name;
                }
            }


            if ($new) {
                return str_replace($m[1], $new, $m[0]);
            } else {
                return '';
            }


        }, $dom->root->innerHtml());


        return $content;
    }


    public function actionPage()
    {
        /**
         * @var OldPage $post
         */

        Yii::$app->language = Config::LANGUAGE_CYRILLIC;
        $translator         = new Translator();

        $posts = OldPage::find()->orderBy(['id' => SORT_DESC])->all();

        foreach ($posts as $post) {

            $p = Page::findOne(['old_id' => $post->id]);

            if ($p == null) {
                $p = new Page();
            }
            $p->old_id = $post->id;
            $p->title  = str_replace("\r\n", "", trim($post->title));

            $p->status = Page::STATUS_PUBLISHED;
            $p->type   = Page::TYPE_PAGE;


            $p->content = $this->convertContent($post->text, false);

            $p->url = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($translator->translateToLatin($p->title))), '-');
            $p->url = trim($p->url, ' -');
            $p->url = str_replace('--', '-', $p->url);
            $p->url = str_replace('--', '-', $p->url);

            if ($image = $post->image) {
                $file     = 'https://zarnews.uz' . $image;
                $ext      = explode('.', $file);
                $name     = md5($file) . '.' . $ext[count($ext) - 1];
                $new_file = Yii::getAlias('@static/uploads/1/') . $name;
                $image    = [
                    'path'     => '1/' . $name,
                    'name'     => time(),
                    'type'     => 'image/jpeg',
                    'order'    => 0,
                    'size'     => 1,
                    'base_url' => Yii::getAlias('@staticUrl/uploads'),
                    'caption'  => ['uz' => '', 'cy' => '', 'ru' => ''],
                ];

                if (file_exists($new_file)) {
                    $p->image = $image;
                    echo $image . PHP_EOL;
                } else {
                    if ($local = $this->getFile($file, $new_file)) {
                        echo $image . PHP_EOL;
                        $p->image = $image;
                    }
                }
            }
            echo $p->title . PHP_EOL;
            if ($p->save()) {
                echo "OK\n";
            }
        }
    }

    public function getFile($url, $local)
    {
        $client = new Client([
            'base_uri' => $url,
        ]);
        for ($i = 0; $i < 2; $i++) {
            try {
                if ($c = $client->get($url)) {
                    if ($c->getHeaderLine('Content-Type') == 'image/jpeg') {
                        if (file_put_contents($local, $c->getBody()->getContents())) {
                            return $local;
                        }
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }

        return false;
    }

    public function actionP(){
        echo Post::deleteAll(['has_russian'=>true]);
    }
}
