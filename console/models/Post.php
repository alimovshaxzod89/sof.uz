<?php

namespace console\models;

use common\components\Config;
use common\components\Translator;
use common\models\Category as NewCategory;
use common\models\Post as NewPost;
use common\models\Tag as NewTag;
use MongoDB\BSON\Timestamp;

/**
 * Class Post
 * @property Category category
 * @property Tag[]    tags
 * @package console\models
 */
class Post extends \common\models\old\OldPost
{
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
                    ->viaTable(TagNode::tableName(), ['page_id' => 'id']);
    }

    public function getNewTagsIds()
    {
        $ids = [];
        if (is_array($this->tags) && count($this->tags)) {
            $ids = array_map(function (Tag $tag) {
                $new = NewTag::find()->where(['old_id' => $tag->id])->one();
                if ($new instanceof NewTag) {
                    return $new->getId();
                }

                return false;
            }, $this->tags);
            $ids = array_filter($ids);
        }

        return $ids;
    }

    public function getCategoriesArray()
    {
        $categories = [];
        if ($this->category instanceof Category) {
            $cat = NewCategory::find()->where(['old_id' => $this->category->id])->one();
            if ($cat instanceof NewCategory) {
                $categories[] = $cat->_id;
            }
        }

        return $categories;
    }

    public function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (curl_exec($ch) !== FALSE) {
            return true;
        } else {
            return false;
        }
    }

    public function toMongo($author = null)
    {
        $image             = [];
        $slug              = $this->slug ?: $this->title;
        $slug              = Translator::getInstance()->translateToLatin($slug);
        $slug              = trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', strtolower($slug)), '-');
        $categories        = $this->getCategoriesArray();
        $tags              = $this->getNewTagsIds();
        $type              = $this->photo == 1 ? NewPost::TYPE_GALLERY : NewPost::TYPE_NEWS;
        $status            = $this->status ? NewPost::STATUS_PUBLISHED : NewPost::STATUS_DRAFT;
        $image['base_url'] = \Yii::getAlias('@staticUrl/uploads');
        $image['path']     = str_replace(['https://sof.uz/files/uploads/', 'http://sof.uz/files/uploads/'], ['', ''], $this->img);
        $img               = pathinfo($this->img);

        if (is_array($img) && isset($img['filename'])) {
            $imageName = crc32($img['filename']) . '.' . $img['extension'];
            $path      = \Yii::getAlias('@static/uploads');

            try {
                $rand = rand(0, 9);
                set_time_limit(0);
                $save = file_put_contents($path . DS . $rand . DS . $imageName, fopen($this->img, 'r'));
                if ($save)
                    $image['path'] = $rand . '/' . $imageName;

            } catch (\Exception$e) {
                $image['path'] = '';
            }
        }

        $uploadPath = \Yii::getAlias('@staticUrl');
        $content    = str_replace(['https://sof.uz/files/uploads', 'http://sof.uz/files/uploads'], $uploadPath, $this->full);
        $new        = new NewPost([
                                      'scenario'     => NewPost::SCENARIO_CONVERT,
                                      'old_id'       => $this->id,
                                      'title'        => $this->title,
                                      'info'         => $this->short,
                                      'content'      => $content,
                                      'views'        => $this->views,
                                      'slug'         => $slug,
                                      'status'       => $status,
                                      'image'        => $image,
                                      'published_on' => new Timestamp(1, time()),
                                      'type'         => $type,
                                      '_author'      => $author,
                                      '_categories'  => $categories,
                                      '_tags'        => $tags,
                                      'created_at'   => new Timestamp(1, $this->date),
                                  ]);

        if ($new->save()) {
            $new->syncLatinCyrill(Config::LANGUAGE_UZBEK, 1);
            //$this->stdout("Created `{$new->title}` post successfully.\n", Console::FG_GREEN);
            return true;
        }

        return false;
    }
}