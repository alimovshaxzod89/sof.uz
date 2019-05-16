<?php

namespace console\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\FileHelper;

/**
 * Class WPBase
 */
class WPBase extends ActiveRecord
{
    /**
     * @return object
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public static function processContent($content)
    {
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        preg_match('#(?:http?s://)?(?:www\.)?(?:youtube\.com/(?:v/|watch\?v=)|youtu\.be/)([\w-]+)(?:\S+)?#', $content, $match);
        if (count($match) && isset($match[1]) && !empty($match[1])) {
            $embed   = "<iframe width='100%' height='400' src='http://www.youtube.com/embed/$match[1]?autoplay=0' frameborder='0' allowfullscreen></iframe>";
            $content = str_replace($match[0], $embed, $content);
        }

        $content = preg_replace_callback(
            '#\[(contact-form-7)(.+?)?\](?:(.+?)?\[\/(contact-form-7)\])?#',
            function ($matches) {
                return '[contact]';
            },
            $content
        );

        $content = str_replace(
            ['www.terabayt.uz/wp-content/uploads/', 'terabayt.uz/wp-content/uploads/'],
            $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ? ['static.tb.lc/uploads/', 'static.tb.lc/uploads/'] : ['static.terabayt.uz/uploads/', 'static.terabayt.uz/uploads/'],
            $content
        );

        return $content;
    }

    public static function saveAttachment($url = false)
    {
        if ($url) {
            $type = pathinfo($url, PATHINFO_EXTENSION) ?: 'jpg'; //ext
            $base = pathinfo($url, PATHINFO_BASENAME); //name
            $name = md5($url) . '.' . $type;

            $upload = Yii::getAlias("@staticUrl/uploads");
            $path   = mb_substr($name, 0, 1) . DS . mb_substr($name, 1, 1) . DS;
            $dir    = Yii::getAlias("@static/uploads") . DS;

            if (!is_dir($dir . $path)) {
                FileHelper::createDirectory($dir . $path, 0777);
            }

            $saved = $dir . $path . $name;
            /*$file = UploadFromUrl::initWithUrl($url);
            $file->saveAs($saved);*/
            try {
                file_exists($saved) or file_put_contents($saved, file_get_contents($url));

                return [
                    'path'     => $path . $name,
                    'name'     => $base,
                    'base_url' => $upload,
                ];
            } catch (\Exception $e) {

            }
        }

        return [];
    }

    public static function dataToImage($match, $tag = 'img')
    {
        list(, $classes, $url, $type, $end) = $match;
        $name = uniqid() . '.' . $type;

        $path = chr(96 + rand(1, 26)) . DS . chr(96 + rand(1, 26)) . DS;
        $dir  = Yii::getAlias("@static/uploads/") . DS;

        if (!is_dir($dir . $path)) {
            FileHelper::createDirectory($dir . $path, 0777);
        }

        $saved = $dir . $path . $name;

        if (!file_exists($saved)) {
            for ($i = 0; $i < 3; $i++) {
                try {
                    if ($f = file_get_contents($url)) {
                        file_put_contents($saved, $f);
                        $url = Yii::getAlias("@staticUrl/uploads/") . $path . $name;
                    }
                } catch (\Exception $e) {
                }
            }
        }

        //$file = UploadFromUrl::initWithUrl($url);
        //$file->saveAs($saved);

        /*
        if (!file_exists($saved) && $f = file_get_contents($url)) {
            file_put_contents($saved, $f);
            $url = Yii::getAlias("@staticUrl/uploads/") . $path . $name;
        }*/

        return "<" . $tag . $classes . ($tag == 'img' ? 'src' : 'href') . "=\"$url\" $end>";
    }

    public static function check_slug($text = '')
    {
        $arr = [
            '—' => '',
            '-' => '',
            ' ' => '-',
            'й' => 'y',
            'ц' => 'ts',
            'у' => 'u',
            'к' => 'k',
            'е' => 'e',
            'н' => 'n',
            'г' => 'g',
            'ш' => 'sh',
            'щ' => 'sh',
            'ў' => 'o',
            'з' => 'z',
            'х' => 'x',
            'ъ' => '',
            'ф' => 'f',
            'қ' => 'q',
            'ы' => '',
            'в' => 'v',
            'а' => 'a',
            'п' => 'p',
            'р' => 'r',
            'о' => 'o',
            'л' => 'l',
            'д' => 'd',
            'ж' => 'j',
            'э' => 'e',
            'я' => 'ya',
            'ч' => 'ch',
            'с' => 's',
            'м' => 'm',
            'и' => 'i',
            'т' => 't',
            'ь' => '',
            'б' => 'b',
            'ю' => 'yu',
            'ғ' => 'g',
            'ҳ' => 'h',
            'ё' => 'yo',
        ];

        $text = mb_convert_encoding(mb_strtolower($text), 'UTF-8', 'auto');
        $text = str_replace(array_keys($arr), array_values($arr), $text);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $text);
    }
}