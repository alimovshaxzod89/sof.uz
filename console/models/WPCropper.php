<?php

namespace console\models;

use common\components\InterlacedImage;
use common\models\Category;
use common\models\GcmUser;
use common\models\Post;
use League\Flysystem\Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;

/**
 * Class WPBase
 */
class WPCropper
{
    public static $overwrite = false;
    public static $qualities = array(
        'high'   => 90,
        'normal' => 65,
        'low'    => 45,
        'poor'   => 20,
    );
    public static $sizes     = array(
        75 => array('w' => 370, 'h' => 165),
        60 => array('w' => 370, 'h' => 330),
    );

    public static function getCategoryIds()
    {
        $cats = @json_decode(@file_get_contents('http://www.tb.lc/api.php?action=categories'), true);
        if (empty($cats) || $cats == null) {
            $cats = json_decode("[{\"id\":0,\"name\":\"Янгиликлар\",\"slug\":\"news\",\"child\":[{\"id\":3,\"name\":\"Телефон\",\"slug\":\"phone\"},{\"id\":14,\"name\":\"Планшет\",\"slug\":\"tablet\"},{\"id\":18,\"name\":\"Ноутбук\",\"slug\":\"laptop\"},{\"id\":27,\"name\":\"Kомпьютер\",\"slug\":\"pc\"},{\"id\":28,\"name\":\"Камера\",\"slug\":\"camera\"},{\"id\":33,\"name\":\"Телевизор\",\"slug\":\"tv\"},{\"id\":1374,\"name\":\"Аксессуарлар\",\"slug\":\"accessory\"},{\"id\":34,\"name\":\"Янги технологиялар\",\"slug\":\"new-technologies\"},{\"id\":30,\"name\":\"Стартап\",\"slug\":\"startup\"},{\"id\":1,\"name\":\"Бошқа хабарлар\",\"slug\":\"phone\"}]},{\"id\":2,\"name\":\"Тавсиф\",\"slug\":\"review\",\"child\":[{\"id\":36,\"name\":\"Телефон\",\"slug\":\"phone\"},{\"id\":37,\"name\":\"Планшет\",\"slug\":\"tablet\"},{\"id\":38,\"name\":\"Ноутбук\",\"slug\":\"laptop\"},{\"id\":39,\"name\":\"Kомпьютер\",\"slug\":\"pc\"},{\"id\":40,\"name\":\"Камера\",\"slug\":\"camera\"},{\"id\":41,\"name\":\"Телевизор\",\"slug\":\"tv\"},{\"id\":42,\"name\":\"Янги технологиялар\",\"slug\":\"new-technologies\"}]},{\"id\":482,\"name\":\"Маслаҳат\",\"slug\":\"maslahat\",\"child\":[]},{\"id\":35,\"name\":\"Иловалар\",\"slug\":\"apps\",\"child\":[]},{\"id\":1205,\"name\":\"Ўйинлар\",\"slug\":\"games\",\"child\":[]},{\"id\":97,\"name\":\"Нархлар\",\"slug\":\"price\",\"child\":[]}]", true);
        }

        $c = [];
        foreach ($cats as $cat) {
            $c[$cat['id']] = $cat['id'];
            foreach ($cat['child'] as $ch) {
                $c[$ch['id']] = $ch['id'];
            }
        }

        unset($c["0"]);
        unset($c[0]);
        unset($c["1"]);
        unset($c[1]);
        $c[]  = 1;
        $cats = Category::find()->where(['_old' => ['$in' => $c]])->all();
        $ids  = [];
        foreach ($cats as $cat) {
            $ps  = array_values($cat->_posts);
            $ps  = array_map(function ($p) {
                return new ObjectId($p);
            }, $ps);
            $ids = array_merge($ids, $ps);
        }

        return Post::find()->where(['status' => ['$in' => ["publish"]], '_type' => ['$in' => [Post::TYPE_REVIEW, Post::TYPE_POST]]])->all();
    }

    public static function cropper($force = false, $day = 1)
    {
        if (php_sapi_name() != "cli") {
            die;
        }
        $count = 0;
        if ($force) {
            self::$overwrite = ($force == 'overwrite');
            echo "Force Update --> " . (self::$overwrite ? "Image Overwrite\n" : "\n");
        }

        $posts = Post::find()
                     ->andWhere([
                         'status' => ['$in' => ["publish"]],
                         '_type'  => ['$in' => [Post::TYPE_REVIEW, Post::TYPE_POST]],
                     ])
                     ->andWhere(['>', 'updated_at', new Timestamp(1, time() - 86400 * $day)])
                     ->orderBy(['_old' => SORT_ASC])
                     ->all();

        if (count($posts)) {
            foreach ($posts as $post) {
                /* @var $post Post */
                if (!empty($post) && count($post->image) && isset($post->image['path'])) {
                    if ($post->_mb != 1 || $force) {

                        $id    = $post->_old;
                        $image = $post->image['path'];
                        $y     = date('Y', $post->published_on->sec);
                        $m     = date('m', $post->published_on->sec);

                        $oldDir = \Yii::getAlias("@static") . DS . 'uploads' . DS;


                        $file = $oldDir . $image;
                        echo "============================================================\n";
                        echo $post->getDate('php:Y-m-d h:i:m') . " --> $file -->" . $post->title . PHP_EOL;


                        if ($content = self::get_content_with_formatting($post->content, $y . DS . $m)) {


                            $cropDir   = \Yii::getAlias("@static") . DS . 'mobile' . DS;
                            $staticUrl = \Yii::getAlias("@staticUrl/mobile");

                            $folder  = $y . DS . $m . DS;
                            $cropDir .= $folder . $id;

                            if (!is_dir($cropDir)) {
                                FileHelper::createDirectory($cropDir, 0777);
                            }

                            if (file_exists($file)) {
                                $info       = pathinfo($file);
                                $ext        = $info['extension'];
                                $croppedImg = $cropDir . '.' . $ext;
                                $croppedUrl = $staticUrl . DS . $folder . $id . '.' . $ext;
                                echo $croppedUrl . PHP_EOL;

                                if (!is_dir($cropDir)) {
                                    mkdir($cropDir, 0777, true);
                                }

                                $flagFile   = $cropDir . '/.' . crc32($image) . '.log';
                                $hasChanges = !file_exists($flagFile);

                                if ($hasChanges || self::$overwrite || !file_exists($croppedImg)) {
                                    try {
                                        $imagine = Image::getImagine();
                                        $imagine->open($file)->save($croppedImg, ['quality' => 85]);
                                        echo $file . ' --> ' . $croppedImg . PHP_EOL;
                                    } catch (\Exception $e) {
                                        echo 'Error: ' . $e->getMessage() . PHP_EOL;
                                    }
                                }

                                foreach (self::$qualities as $name => $q) {
                                    $qualityImage = $cropDir . '/' . $name . '.' . $ext;

                                    if ($hasChanges || self::$overwrite || !file_exists($qualityImage)) {
                                        try {
                                            InterlacedImage::thumbnail($file, 480, 320)
                                                           ->save($qualityImage, ['quality' => $q]);

                                            echo $file . ' --> ' . $qualityImage . PHP_EOL;
                                        } catch (\Exception $e) {
                                            echo 'Error: ' . $e->getMessage() . PHP_EOL;
                                        }
                                    }
                                }

                                $c = $post->updateAttributes([
                                    'mb_image'   => $croppedUrl,
                                    'mb_content' => $content,
                                    '_mb'        => 1,
                                    'updated_at' => new Timestamp(1, time()),
                                ]);
                                if (!$c) {
                                    file_put_contents($flagFile, json_encode($post->errors));
                                } else {
                                    file_put_contents($flagFile, '');
                                    if ($post->_mb != 1) {
                                        $count++;
                                    }
                                }
                            }
                        }

                        ob_flush();
                        flush();
                    }
                }
            }

            if ($count > 0) {
                //send notification
                self::sendNotification($count . " Posts");
            }
        }
    }

    public static function get_content_with_formatting($content = '', $folder = false)
    {
        $content = preg_replace_callback('/\[([^\]]+)\]([^\[]+)\[([^\[]+)]/i', function ($matches) {
            return $matches[2];
        }, $content);

        $content = preg_replace_callback('#https?://(www.)?youtube\.com/(?:v|embed|watch)([^/^\s]+)\S#i', function ($matches) {
            return "<div class='youtube' onclick='playVideo(\"{$matches[0]}\")'></div>";
        }, $content);

        $content = preg_replace_callback('#https?://(www.)?youtu\.be/([\w-]+)#i', function ($matches) {
            return "<div class='youtube' onclick='playVideo(\"{$matches[0]}\")'></div>";
        }, $content);

        $content = preg_replace('/\[(.*)\]/i', '', $content);;
        $content = str_replace(']]>', ']]&gt;', $content);

        /* $content = preg_replace_callback('#<a\s(?:href=[\'"]https?://(www.)?terabayt\.uz/wp-content(.*?)[\'"]).*?><img[^>]*src *= *["\']?([^"\']*)([^>]*)>\s?</a>#is', function ($matches) {
             $link = $matches[3];
             $id   = 'crp__' . crc32($link);
             $link = cropImage($link, $id);

             return "<div class='imgbox'><span>{tap_to_load}</span><img data-src='$link' data-img='$id' id='$id' alt=' '/></div>";
         }, $content);*/
        /*
                $content = preg_replace_callback('/<img [^>]+ (style=".*?")>/i', function ($matches) {
                    //print_r($matches);die;
                }, $content);*/


        $content = preg_replace_callback("/(<(img|a)[^>]*src *= *[\"']?)([^\"']*)([^>]*)>/i", function ($matches) use ($folder) {
            $link = $matches[3];
            $id   = 'crp__' . crc32($link);
            if ($link = self::cropImage($link, $id, $folder)) {
                return "<div class='imgbox'><span>{tap_to_load}</span><img data-src='$link' data-img='$id' id='$id' alt=' '/></div>";
            }

            return "";
        }, $content);

        $content = wpautop(convert_chars($content));
        $content = str_replace(">p>", ">", $content);

        return $content;
    }

    public static function cropImage($image, $id, $folder)
    {

        $upl     = 'uploads';
        $rootDir = \Yii::getAlias("@static") . DS;

        $ext = explode("?", pathinfo($image, PATHINFO_EXTENSION));
        if (in_array($ext[0], ['jpg', 'jpeg', 'png', 'gif'])) {
            $ext = $ext[0];
        } else {
            $ext = 'jpg';
        }
        $fileName = crc32($image) . '.' . $ext;

        $newDir = $rootDir . $upl . DS . $fileName[0] . DS . $fileName[1] . DS;
        FileHelper::createDirectory($newDir);

        if (!file_exists($newDir . $fileName))
            for ($i = 0; $i < 4; $i++) {
                try {
                    if ($content = @file_get_contents(str_replace("https://", "http://", $image))) {
                        file_put_contents($newDir . $fileName, $content);
                        break;
                    }

                } catch (Exception $e) {
                    echo $e->getMessage() . PHP_EOL;
                }
            }

        if (file_exists($newDir . $fileName)) {
            $cropDir   = \Yii::getAlias("@static") . DS . 'mobile' . DS;
            $staticUrl = \Yii::getAlias("@staticUrl/mobile/");

            $file = $newDir . $fileName;
            $info = pathinfo($file);
            $ext  = $info['extension'];

            $dir        = $cropDir . $folder . DS . $id;
            $croppedUrl = $staticUrl . $folder . '/' . $id . '/poor.' . $ext;


            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $flagFile   = $dir . '/.' . $id . '.log';
            $hasChanges = !file_exists($flagFile);

            foreach (self::$qualities as $name => $q) {
                $fileImage = $dir . '/' . $name . '.' . $info['extension'];
                if ($hasChanges || self::$overwrite || !file_exists($fileImage)) {
                    try {
                        InterlacedImage::thumbnail($file, 620, null)
                                       ->save($fileImage, ['quality' => $q]);

                        echo $file . ' --> ' . $fileImage . PHP_EOL;
                    } catch (\Exception $e) {
                        echo 'Error: ' . $e->getMessage() . PHP_EOL;
                    }
                }
            }
            file_put_contents($flagFile, '');

            return $croppedUrl;
        }
    }

    public static function sendNotification($message = '')
    {
        echo "sendNotification: $message\n";

        $ids = array_chunk(self::getDevicesList(), 1000);
        foreach ($ids as $item) {
            self::sendMessage($item, $message);
        }
    }

    public static function getDevicesList()
    {
        $devices = [];
        $gcm     = GcmUser::find()->all();
        /* @var $gcm GcmUser */
        if (count($gcm)) {
            array_merge($devices, array_map(function ($v) {
                return $v->gcm_regid;
            }, $gcm));
        }
        return $devices;
    }

    public static function sendMessage($ids, $message)
    {
        $fields  = array(
            'registration_ids' => $ids,
            'data'             => array('update' => $message),
        );
        $headers = array(
            'Authorization: key=AIzaSyChKxE34wV_dJUXBBBPZXtf53kj_jZmNJY',
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://gcm-http.googleapis.com/gcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);

        $answer = @json_decode($result, true);
        if (!$answer) {
            echo var_dump($result);
            echo curl_error($ch);
        }
        //print_r($answer);
        curl_close($ch);

        $fail = [];
        $succ = [];

        if (isset($answer['results']) && count($answer['results'])) {
            foreach ($answer['results'] as $id => $data) {
                if (isset($ids[$id])) {
                    if (isset($data['error'])) {
                        $fail[] = $ids[$id];
                    } else {
                        $succ[] = $ids[$id];
                    }
                }
            }
        }

        if (count($fail)) {
            $gcm = GcmUser::find(['gcm_regid' => ['$in' => $fail]])->all();
            foreach ($gcm as $v) {
                /* @var $v GcmUser */
                $v->updateAttributes(['fail' => ($v->fail + 1)]);
            }

            GcmUser::deleteAll(['fail' => ['$gt' => 10]]);
        }

        if (count($succ)) {
            $c = count($succ);
            echo "Notified: $c\n";

            $gcm = GcmUser::find(['success' => ['$in' => $succ]])->all();
            foreach ($gcm as $v) {
                /* @var $v GcmUser */
                $v->updateAttributes(['fail' => ($v->success + 1)]);
            }
        }
    }
}