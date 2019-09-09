<?php

namespace api\models\v1;

use common\components\InterlacedImage;
use common\models\Post as PostModel;
use GuzzleHttp\Client;
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
            'has_gallery'  => function () {
                return $this->has_gallery ? true : false;
            },
            'hide_image'   => function () {
                return boolval($this->hide_image) || empty($this->image) || $this->image == null;
            },
            'has_priority' => function () {
                return $this->is_main ? true : false;
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

    public function isPushNotificationExpired()
    {
        $sendAnd = $this->getPushedOnTimeDiffAndroid();

        return $sendAnd == 0 || $sendAnd > 3600;
    }

    public function getPushedOnTimeDiffAndroid()
    {
        if ($this->pushed_on) {
            return time() - $this->pushed_on->getTimestamp();
        }

        return 0;
    }

    public function sendPushNotification()
    {
        $result = false;
        if (Config::get(Config::CONFIG_PUSH_TO_ANDROID)) {
            $result = $result || $this->sendPushNotificationAndroid() != false;
        }

        return $result;
    }

    public function sendPushNotificationAndroid($force = false)
    {
        if ($this->getPushedOnTimeDiffAndroid() == 0 || $force) {
            $client = new Client(['base_uri' => 'https://fcm.googleapis.com/']);

            $params = [
                'json'    => [
                    'to'       => YII_DEBUG ? '/topics/allTest' : '/topics/all',
                    'priority' => 'normal',
                    'data'     => [
                        'post_id'     => $this->getId(),
                        'has_russian' => $this->has_russian ? "yes" : "no",
                        'has_uzbek'   => $this->has_uzbek ? "yes" : "no",
                    ],
                ],
                'headers' => [
                    'Authorization' => getenv('FCM_KEY'),
                ],
            ];

            $result = $client->post('fcm/send', $params);
            $result = $result->getBody()->getContents();

            if ($data = json_decode($result, true)) {
                if (isset($data['message_id'])) {
                    $this->updateAttributes(['pushed_on' => call_user_func($this->getTimestampValue())]);
                }
                return $data;
            }
        }

        return false;
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

    /**
     * @param array  $img
     * @param int    $width
     * @param int    $height
     * @param string $manipulation
     * @param bool   $watermark
     * @param int    $quality
     * @return string
     * @throws \yii\base\Exception
     */
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

            $cropFull  = null;
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
