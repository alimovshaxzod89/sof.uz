<?php

namespace api\controllers\v1;

use api\models\v1\Category;
use api\models\v1\Tag;
use common\components\Config;
use common\models\Page;
use Yii;

class HomeController extends ApiController
{
    public function actionCategories()
    {
        return ['items' => $this->getCategoryList()];
    }

    public function actionTags($limit = 20)
    {
        $tags = Tag::find()
                   ->orderBy(['count_l5d' => SORT_DESC])
                   ->limit($limit)
                   ->all();

        return ['items' => $tags];
    }

    private function getCategoryList()
    {
        $root = Config::get(Config::CONFIG_MENU_CATEGORY);
        return Category::getCategoryTree([], $root);
    }

    public function actionPage($slug)
    {
        if ($page = Page::findOne(['slug' => $slug, 'status' => Page::STATUS_PUBLISHED])) {
            return ['content' => $page->content];
        }

        return ['content' => ''];
    }

    public function actionAssets()
    {
        $tags = Tag::find()
                   ->orderBy(['count_l5d' => SORT_DESC])
                   ->limit(10)
                   ->all();

        if ($contact = Page::findOne(['slug' => 'loyiha-haqida'])) {
            $contact = $this->renderPartial('@frontend/views/page/view.php', ['model' => $contact]);
            $contact = "<div class=\"nav-off-canvas\">$contact</div>";
        }

        return [
            'css'        => file_get_contents(Yii::getAlias('@frontend/assets/app/css/mobile.css')),
            'js'         => file_get_contents(Yii::getAlias('@frontend/assets/app/js/mobile.js')),
            'tags'       => $tags,
            'contact'    => $contact,
            'categories' => $this->getCategoryList(),
        ];
    }
}