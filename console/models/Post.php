<?php

namespace console\models;

use common\models\Post as NewPost;
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
                $new = \common\models\Tag::find()->where(['old_id' => $tag->id])->one();
                if ($new instanceof \common\models\Tag) {
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
            $cat = \common\models\Category::find()->where(['old_id' => $this->category->id])->one();
            if ($cat instanceof \common\models\Category) {
                $categories[] = $cat->getId();
            }
        }

        return $categories;
    }

    public function toMongo()
    {
        $categories = $this->getCategoriesArray();
        $tags = $this->getNewTagsIds();
        $status     = $this->status ? NewPost::STATUS_PUBLISHED : NewPost::STATUS_DRAFT;
        $new        = new NewPost([
                                      'old_id'      => $this->id,
                                      'title'       => $this->title,
                                      'content'     => $this->full,
                                      'views'       => $this->views,
                                      'url'         => $this->slug,
                                      'status'      => $status,
                                      'type'        => NewPost::TYPE_NEWS,
                                      '_categories' => $categories,
                                      '_tags'       => $tags,
                                      'created_at'  => new Timestamp(1, $this->date),
                                  ]);

        if ($new->save()) {
            //$this->stdout("Created `{$new->title}` post successfully.\n", Console::FG_GREEN);
            return true;
        }

        return false;
    }
}