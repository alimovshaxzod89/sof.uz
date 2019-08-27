<?php

namespace api\models\v1;

use common\components\InterlacedImage;
use common\models\Post as PostModel;
use Imagine\Image\ManipulatorInterface;
use MongoDB\BSON\Timestamp;
use yii\helpers\FileHelper;


class Post extends PostModel
{
    private static $priorityCounter = 0;
    private static $priorityAdded   = false;

    public $_similarTitle;

    public function fields()
    {
        return [
            'id'           => 'id',
            'short_id'     => 'short_id',
            'title'        => 'title',
            'info'         => 'info',
            'image'        => 'mobile_image',
            'read_min'     => 'read_min',
            'has_gallery'  => 'has_gallery',
            'hide_image'   => function () {
                return boolval($this->hide_image) || empty($this->image) || $this->image == null;
            },
            'has_priority' => function () {
                return $this->is_main;
            },
            'categories'   => function () {
                return $this->category ? $this->category->id : '';
            },

            'published_on' => function () {
                return ($this->created_at instanceof Timestamp) ? $this->published_on->getTimestamp() : $this->published_on;
            },

            'updated_at' => function () {
                return ($this->updated_at instanceof Timestamp) ? $this->updated_at->getTimestamp() : $this->updated_at;
            },

            'view_url' => function () {
                return $this->getShortViewUrl();
            },
        ];
    }

    public function extraFields()
    {
        return [
            'content' => function () {
                if (mb_strpos($this->content, 'twitter') !== false) {
                    $this->content .= '<script src="https://platform.twitter.com/widgets.js" async charset="utf-8"></script>';
                }

                if (count($this->getCards())) {
                    return "<div class='card-post'>{$this->content}</div>";
                };
                return $this->content;
            },
            'tags'    => function () {
                return Tag::find()->where(['_id' => $this->getConvertedTags()])->all();
            },

            'similar' => function () {
                $similar             = $this->getSimilarPosts(6);
                $this->_similarTitle = __('Aloqador maqolalar');

                if (count($similar) < 2) {
                    $similar = self::find()
                                   ->where([
                                       'status'    => Post::STATUS_PUBLISHED,
                                       'is_mobile' => true,
                                       '_id'       => ['$nin' => [$this->_id]],
                                   ])
                                   ->addOrderBy(['published_on' => SORT_DESC])
                                   ->limit(6)
                                   ->all();

                    $this->_similarTitle = __('So\'nggi yangiliklar');
                }

                return $similar;
            },

            'similarTitle' => '_similarTitle',
            'gallery'      => function () {
                return empty($this->gallery_items) ? [] : $this->gallery_items;
            },

        ];
    }

    private static $mobileQualities = [
        'normal' => 80,
    ];

    private static $mobileSizes = [
        'small'  => 220,
        'large'  => 720,
        'medium' => 480,
    ];

    public static function getCropImage($img = [], $width = 270, $height = 347, $manipulation = ManipulatorInterface::THUMBNAIL_INSET, $watermark = false, $quality = 80)
    {
        $dir     = \Yii::getAlias("@static") . DS . 'uploads' . DS;
        $cropDir = \Yii::getAlias("@static") . DS . 'crop' . DS . 'm' . DS;

        $img = (array)$img;


        if (!is_dir($cropDir)) {
            FileHelper::createDirectory($cropDir, 0777);
        }


        $cropUrl = false;
        $default = 'img=self&';
        if (!empty($img) && is_array($img) && isset($img['path'])) {

            if (!file_exists($dir . $img['path'])) {
                $img['path'] = 'img-placeholder.png';
                $img['name'] = 'img-placeholder.png';
                $quality     = 95;
            }

            if (strpos($img['name'], 'placeholder')) {
                $default = 'img=placeholder&';
            }

            $img['path'] = preg_replace('/[\d]{2,4}_[\d]{2,4}_/', '', $img['path']);
            $imagePath   = $dir . $img['path'];
            $info        = pathinfo($imagePath);

            $imageName = md5($img['path']) . '.' . $info['extension'];

            $cropPath = $imageName[0] . DS . $imageName[1] . DS;
            if (!is_dir($cropDir . $cropPath)) {
                FileHelper::createDirectory($cropDir . $cropPath, 0777);
            }

            foreach (self::$mobileSizes as $widthName => $width) {
                foreach (self::$mobileQualities as $qualityName => $quality) {
                    $cropName = $widthName . '_' . $qualityName . '_' . $imageName;
                    $cropFull = $cropDir . $cropPath . $cropName;

                    $cropUrl = \Yii::getAlias('@staticUrl/crop/m/') . $cropPath . $cropName;

                    if (file_exists($imagePath)) {
                        if (!file_exists($cropFull)) {
                            if ($watermark) {
                                InterlacedImage::thumbnailWithWatermark($imagePath, $width, $height, $manipulation)
                                               ->save($cropFull, ['quality' => $quality]);


                            } else {
                                InterlacedImage::thumbnail($imagePath, $width, $height, $manipulation)
                                               ->save($cropFull, ['quality' => $quality]);

                                if (YII_DEBUG) {
                                    echo $cropUrl . PHP_EOL;
                                }
                            }
                        }
                    }
                }
            }
            return $cropUrl . "?{$default}v=" . filemtime($cropFull);
        } else {
            $img['path'] = 'img-placeholder.png';
            $img['name'] = 'img-placeholder.png';
            return self::getCropImage($img, $width, $height);
        }
    }
}
