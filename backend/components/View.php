<?php

namespace backend\components;

use backend\assets\BackendAsset;
use common\models\Admin;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\Url;
use yii\web\IdentityInterface;

class View extends \yii\web\View
{
    /** @var  ContextInterface */
    public    $context;
    protected $_menu = [
        'news'   => [
            'icon'  => 'globe',
            'label' => 'News',
            'url'   => '#',
            'items' => [
                'post/index'     => [
                    'label' => 'Posts',
                    'url'   => 'post/index',
                ],
                'post/photo'     => [
                    'label' => 'Photo News',
                    'url'   => 'post/photo',
                ],
                'post/video'     => [
                    'label' => 'Video News',
                    'url'   => 'post/video',
                ],
                'post/columnist' => [
                    'label' => 'Columnists',
                    'url'   => 'post/columnist',
                ],
                'category/index' => [
                    'label' => 'Categories',
                    'url'   => 'category/index',
                ],
                'tag/index'      => [
                    'label' => 'Tags',
                    'url'   => 'tag/index',
                ],
                'post/draft'     => [
                    'label' => 'Drafts',
                    'url'   => 'post/draft',
                ],
                'post/stat'      => [
                    'label' => 'Stat',
                    'url'   => 'post/stat',
                ],
            ],
        ],
        'store'  => [
            'icon'  => 'folder-open',
            'label' => 'Web-site',
            'url'   => '#',
            'items' => [
                'page/index'    => [
                    'label' => 'Pages',
                    'url'   => 'page/index',
                ],/*
                'poll/index'    => [
                    'label' => 'Polls',
                    'url'   => 'poll/index',
                ],
                'rating/index'     => [
                    'label' => 'Ratings',
                    'url'   => 'rating/index',
                ],*/
                'comment/index' => [
                    'label' => 'Comments',
                    'url'   => 'comment/index',
                ],
                'error/index'   => [
                    'label' => 'Errors',
                    'url'   => 'error/index',
                ],
            ],
        ],
        'adv'    => [
            'icon'  => 'bullhorn',
            'label' => 'Advertising',
            'url'   => '#',
            'items' => [
                'adv/index'   => [
                    'label' => 'Advertises',
                    'url'   => 'adv/index',
                ],
                'place/index' => [
                    'label' => 'Places',
                    'url'   => 'place/index',
                ],
            ],
        ],
        'users'  => [
            'icon'  => 'user',
            'label' => 'Users',
            'url'   => '#',
            'items' => [
                /*'customer/index' => [
                    'label' => 'Users',
                    'url'   => 'user/index',
                ],
                'blogger/index'  => [
                    'label' => 'Authors',
                    'url'   => 'blogger/index',
                ],*/
                'admin/index' => [
                    'label' => 'Authors',
                    'url'   => 'admin/index',
                ],
            ],
        ],
        'system' => [
            'icon'  => 'gear',
            'label' => 'System',
            'url'   => 'system/index',
            'items' => [
                'system/translation'   => [
                    'label' => 'UI Translation',
                    'url'   => 'system/translation',
                ],
                'system/dictionary'    => [
                    'label' => 'System Dictionary',
                    'url'   => 'system/dictionary',
                ],
                'system/login'         => [
                    'label' => 'Login History',
                    'url'   => 'system/login',
                ],
                'system/log'           => [
                    'label' => 'Admin Logs',
                    'url'   => 'system/log',
                ],
                'system/configuration' => [
                    'label' => 'Configuration',
                    'url'   => 'system/configuration',
                ],
                'system/backup'        => [
                    'label' => 'Backups',
                    'url'   => 'system/backup',
                ],
                'system/trash'         => [
                    'label' => 'Trashed Posts',
                    'url'   => 'system/trash',
                ],
            ],
        ],
    ];

    public function getMenuItems()
    {
        $menu = array();

        if ($admin = $this->_user()) {
            $menu = Yii::$app->cache->get(Admin::CACHE_KEY_ADMIN_MENU . $admin->id . Yii::$app->language);
            if ($menu === false) {
                $menu = $this->_menu;

                foreach ($menu as $id => &$item) {
                    $item['label'] = __(trim($item['label']));
                    if (isset($item['items']) && !empty($item['items'])) {
                        foreach ($item['items'] as $p => &$childItem) {
                            if (!$this->_user()->canAccessToResource($childItem['url'])) {
                                unset($menu[$id]['items'][$p]);
                            }
                            $childItem['label'] = __($childItem['label']);
                            $childItem['url']   = Url::to([$childItem['url']]);
                        }
                        if (count($menu[$id]['items']) == 0 && !$admin->canAccessToResource($item['url'])) {
                            unset($menu[$id]);
                        }
                    }
                    if (!$admin->canAccessToResource($item['url']) && (!isset($item['items']) || count($item['items']) == 0)) {
                        unset($menu[$id]);
                    }
                    $item['url'] = Url::to([$item['url']]);
                }
                Yii::$app->cache->set(Admin::CACHE_KEY_ADMIN_MENU . $admin->id . Yii::$app->language, $menu, 3200, new TagDependency(['tags' => Admin::CACHE_TAG_ADMIN_MENU]));
            }
        }

        return $menu;
    }

    public function getImageUrl($name)
    {
        return $this->getAssetManager()->getBundle(BackendAsset::class)->baseUrl . '/' . $name;
    }

    /**
     * @return Admin|IdentityInterface
     */
    public function _user()
    {
        return $this->context->_user();
    }

    public function getFullPath()
    {
        return Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
    }
}