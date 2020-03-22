<?php

namespace api\models\v1;

use common\components\Config;
use common\components\InterlacedImage;
use common\models\Post as PostModel;
use GuzzleHttp\Client;
use Imagine\Image\ManipulatorInterface;
use MongoDB\BSON\Timestamp;
use yii\helpers\FileHelper;
use yii\helpers\Json;


class Post extends PostModel
{
    private static $priorityCounter = 0;
    private static $priorityAdded = false;

    public $_similarTitle;

    public function fields()
    {
        return [
            'id' => 'id',
            'short_id' => 'short_id',
            'title' => 'title',
            'views' => 'views',
            'image' => 'mobile_image',
            'hide_image' => function () {
                return boolval($this->hide_image) || empty($this->image) || $this->image == null;
            },
            'has_priority' => function () {
                return $this->is_main ? true : false;
            },
            'category' => function () {
                return $this->category ? $this->category->id : '';
            },

            'published_on' => function () {
                return ($this->created_at instanceof Timestamp) ? $this->published_on->getTimestamp() : $this->published_on;
            },

            /*'updated_at' => function () {
                return ($this->updated_at instanceof Timestamp) ? $this->updated_at->getTimestamp() : $this->updated_at;
            },*/

            'view_url' => function () {
                return $this->getShortViewUrl();
            },
        ];
    }

    public function hasPriority()
    {
        return $this->is_main;// || $this->is_tagged;
    }


    public function sendPushNotificationAndroid($force = false)
    {
        $publisher = \Yii::$app->get(\common\models\Post::SOCIAL_ANDROID);

        return $publisher->publish($this, true);
    }


    public function sendPushNotificationIOS($force = false)
    {
        $publisher = \Yii::$app->get(\common\models\Post::SOCIAL_ANDROID);

        return $publisher->publishIos($this, true);
    }

    public function extraFields()
    {
        return [
            'content' => function () {
                if (count($this->getCards())) {
                    return "<div class='card-post'>{$this->content}</div>";
                };
                return $this->content;
            },
            'tags' => function () {
                return Tag::find()->where(['_id' => $this->getConvertedTags()])->all();
            },

            'similar' => function () {
                $similar = $this->getSimilarPosts(6);
                $this->_similarTitle = __('Aloqador maqolalar');

                if (count($similar) < 2) {
                    $similar = self::find()
                        ->where([
                            'status' => Post::STATUS_PUBLISHED,
                            '_id' => ['$nin' => [$this->_id]],
                        ])
                        ->addOrderBy(['ad_time' => SORT_DESC, 'published_on' => SORT_DESC])
                        ->limit(6)
                        ->all();

                    $this->_similarTitle = __('So\'nggi yangiliklar');
                }

                return $similar;
            },

            'similarTitle' => '_similarTitle',
            'gallery' => function () {
                return empty($this->gallery_items) ? [] : $this->gallery_items;
            },
            'author' => function () {
                if ($this->hasAuthor()) {
                    return [
                        'id' => $this->author->getId(),
                        'name' => $this->author->getFullName(),
                        'image' => $this->author->getCroppedImage(90, 90, 1),
                        'info' => $this->author->description,
                    ];
                }

                return null;
            }

        ];
    }

    private static $mobileQualities = [
        'normal' => 80,
    ];

    private static $mobileSizes = [
        'small' => 220,
        'large' => 720,
        'medium' => 480,
    ];

    /**
     * @param array $img
     * @param int $width
     * @param int $height
     * @param string $manipulation
     * @param bool $watermark
     * @param int $quality
     * @return string
     * @throws \yii\base\Exception
     */
    public static function getCropImage($img = [], $width = 270, $height = 347, $manipulation = ManipulatorInterface::THUMBNAIL_INSET, $watermark = false, $quality = 80)
    {
        $dir = \Yii::getAlias("@static") . DS . 'uploads' . DS;
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
                $quality = 95;
            }

            if (strpos($img['name'], 'placeholder')) {
                $default = 'img=placeholder&';
            }

            $img['path'] = preg_replace('/[\d]{2,4}_[\d]{2,4}_/', '', $img['path']);
            $imagePath = $dir . $img['path'];
            $info = pathinfo($imagePath);

            $cropFull = null;
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
