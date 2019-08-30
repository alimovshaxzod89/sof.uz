<?php

namespace common\models;

use common\components\Config;
use common\components\Translator;
use Imagine\Image\ManipulatorInterface;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "category".
 * @property string   $name
 * @property string   $old_id
 * @property string   $slug
 * @property string   $position
 * @property string   $_products
 * @property integer  $count_posts
 * @property string   $_filter
 * @property string   $_domain
 * @property array    $_sort
 * @property string   $_type
 * @property string   $parent
 * @property Category $parent_category
 * @property string   $is_hidden
 * @property string   $is_home
 * @property string   $is_menu
 * @property string   $home_order
 * @property mixed    $created_at
 * @property mixed    $updated_at
 * @property array    image
 * @property Post[]   posts
 */
class Category extends MongoModel
{
    protected $_translatedAttributes = ['name', 'description', 'meta_description', 'meta_keywords', 'page_title'];
    protected $_booleanAttributes    = ['is_home', 'is_menu'];
    protected $_integerAttributes    = ['home_order'];

    /**
     * @var Category[]
     */
    public $child  = [];
    public $_posts = [];

    public static function getAsOption()
    {
        $all = self::find()->all();
        return ArrayHelper::map($all, 'id', 'name');
    }

    public function init()
    {

        parent::init();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            '_filter',
            '_type',
            '_sort',
            '_children',
            'old_id',
            'position',
            'parent',
            'color',
            'name',
            'slug',
            'image',
            'is_home',
            'is_menu',
            'is_hidden',
            'description',
            'meta_description',
            'meta_keywords',
            'page_title',
            'count_posts',
            '_domain',
            'home_order',
        ]);
    }

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'color'], 'safe'],
            [['page_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 1024],

            [['slug', 'name'], 'required', 'on' => ['insert', 'update']],
            [['slug'], 'match', 'pattern' => '/^[\/a-z]{1,64}[\/a-z0-9-]{1,128}$/', 'message' => __('Use only english alpha and numeric characters')],

            [['slug'], 'unique', 'on' => ['insert', 'update']],
            [['search', 'home_order'], 'safe'],
            [$this->_booleanAttributes, 'safe'],
            [['parent'], 'default', 'value' => false],
        ];
    }

    public function getBooleanAttributes()
    {
        return $this->_booleanAttributes;
    }


    public function getParentCat()
    {
        return self::findOne($this->parent);
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->position = self::find()->count() + 1;
        }

        $translator = new Translator();
        if (!$this->slug) {
            $this->slug = trim(preg_replace('/[^A-Za-z0-9-_\/]+/', '-', strtolower($translator->translateToLatin($this->name))), '-');
        }

        if (Yii::$app->language == Config::LANGUAGE_UZBEK) {
            if ($this->getTranslation('name', Config::LANGUAGE_CYRILLIC, true) == '') {
                $this->setTranslation('name', $translator->translateToCyrillic($this->name), Config::LANGUAGE_CYRILLIC);
            }
        } else {
            if ($this->getTranslation('name', Config::LANGUAGE_UZBEK, true) == '') {
                $this->setTranslation('name', $translator->translateToLatin($this->name), Config::LANGUAGE_UZBEK);
            }
        }

        return parent::beforeSave($insert);
    }

    public static function getRootCategories()
    {
        return self::find()->where(['parent' => ['$eq' => false]])->orderBy('position')->all();
    }

    public static function getParentCategory($parent)
    {
        return self::findOne(['_id' => $parent]);
    }

    public static function getChildCategories($category)
    {
        return self::findAll(['parent' => $category]);
    }

    public static function getRootCategoriesAsOption()
    {
        return ArrayHelper::map(self::getRootCategories(), function (self $model) {
            return $model->getId();
        }, 'name');
    }

    public static function getCategoryTreeAsArray($selected = [], $root = false)
    {
        $tree = self::_tree(self::getCategoryTree([], $root), $selected);

        foreach ($tree as &$item) {
            $expandedItem = false;
            foreach ($item['children'] as &$child) {
                $childExpanded = false;
                foreach ($child['children'] as &$ch) {
                    $cExpanded = false;
                    foreach ($ch['children'] as &$c) {
                        $cExpanded = $cExpanded || $c['selected'];
                    }
                    $ch['expanded'] = $ch['expanded'] || $cExpanded;
                    $childExpanded  = $childExpanded || $cExpanded || $ch['selected'];
                }
                $child['expanded'] = $child['expanded'] || $childExpanded;
                $expandedItem      = $expandedItem || $childExpanded || $child['selected'];
            }
            $item['expanded'] = $item['expanded'] || $expandedItem;
        }

        return $tree;
    }

    protected static function _tree($categories, $selected)
    {
        $result = [];
        foreach ($categories as $category) {
            $result[] = [
                'title'    => $category->name,
                'selected' => in_array($category->getId(), $selected),
                'key'      => $category->getId(),
                'expanded' => false,
                'folder'   => count($category->child) > 0,
                'children' => count($category->child) ? self::_tree($category->child, $selected) : [],
            ];
        }
        return $result;
    }

    /**
     * @param array $where
     * @param bool  $root
     * @param bool  $self
     * @return Category[]|array|mixed
     */
    public static function getCategoryTree($where = [], $root = false, $self = false)
    {
        /**
         * @var $category Category
         */

        $all        = self::find()
                          ->where($where)
                          ->orderBy('position')
                          ->all();
        $categories = [];
        $moved      = [];

        foreach ($all as $category) {
            $categories[$category->getId()] = $category;
        }
        foreach ($all as $category) {
            if ($parent = $category->parent) {
                if (isset($categories[$parent])) {
                    $categories[$parent]->child[] = $category;
                    $moved[]                      = $category->id;
                }
            }
        }

        foreach ($moved as $id) {
            unset($categories[$id]);
        }

        if ($root && isset($categories[$root])) {
            return $self ? $categories[$root] : $categories[$root]->child;
        }

        return array_values($categories);
    }

    public static function sortTree($data, $parent = false)
    {
        $pos = 0;
        foreach ($data as $item) {
            if ($category = self::findOne($item['id'])) {
                $category->parent   = $parent;
                $category->position = $pos++;
                $category->save();
                if (isset($item['children']))
                    self::sortTree($item['children'], $category->getId());
            }
        }
    }

    public function getCroppedImage($width = 870, $height = 260)
    {
        if ($this->image) {
            return parent::getCropImage($this->image, $width, $height, ManipulatorInterface::THUMBNAIL_OUTBOUND);
        }

        return false;
    }

    public function searchByParent($parent)
    {
        $query = self::find()->where(['parent' => $parent]);

        $dataProvider = new ActiveDataProvider([
                                                   'query'      => $query,
                                                   'pagination' => [
                                                       'pageSize' => 5,
                                                   ],
                                               ]);

        return $dataProvider;
    }

    public function getViewUrl($params = [])
    {
        if (strpos($this->slug, '/category/') !== false) {
            return Url::to(array_merge(['/category/view', 'slug' => mb_substr($this->slug, 10)], $params), true);
        }
        return Url::to(array_merge(['/' . $this->slug], $params), true);
    }

    public function afterDelete()
    {
        if ($image = $this->image) {
            $dir = Yii::getAlias('@static/uploads');
            if (isset($image['path']) && file_exists($dir . DS . $image['path'])) {
                unlink($dir . DS . $image['path']);
            }
        }

        Category::deleteAll(['parent' => $this->id]);

        parent::afterDelete();
    }

    public function afterFind()
    {
        parent::afterFind();
    }

    /**
     * @return Post[]
     */
    public function getPosts()
    {
        $query = Post::find()
                     ->where(['_categories' => ['$elemMatch' => ['$in' => [$this->_id]]]])
                     ->orderBy(['published_on' => SORT_DESC]);
        return $query->all();
    }

    public static function indexPostCount()
    {
        foreach (self::find()->all() as $category) {
            $count = Post::find()
                         ->where(['_categories' => ['$elemMatch' => ['$in' => [$category->id]]]])
                         ->count();

            $category->updateAttributes(['count_posts' => $count]);

            echo $category->name . ' ' . $category->count_posts . PHP_EOL;
        }
    }

    public function hasChild()
    {
        return count($this->child) > 0;
    }

}
