<?php
/**
 * Created by PhpStorm.
 * User: shavkat
 * Date: 9/8/15
 * Time: 11:32 AM
 */

namespace console\controllers;

use GuzzleHttp\Client;
use Yii;
use yii\console\Controller;

class ApiController extends Controller
{
    protected $_localUrl = 'http://api.xabar.lc/v1/';
    protected $_liveUrl  = 'https://api.xabar.uz/v1/';
    protected $_lang     = 'uz-UZ';

    public function actionCategories()
    {
        $this->request('home/categories');
    }

    public function actionTags()
    {
        $this->request('home/tags');
    }

    public function actionPosts($page = 0, $type = 'all')
    {
        $this->request('post/list', ['page' => $page, 'type' => $type]);
    }

    protected function request($url, $get = [], $post = [])
    {
        $client = new Client([
                                 'base_uri' => YII_DEBUG ? $this->_localUrl : $this->_liveUrl,
                                 'verify'   => false,
                             ]);

        $get['l'] = $this->_lang;

        if (!YII_DEBUG) {
            $get['t'] = time();
            $get['h'] = md5($get['t'] . '#_#' . $get['t']);
        }

        $url = $url . '?' . http_build_query($get);

        if ($post && count($post)) {
            $result = $client->post($url, ['form_params' => $post]);
        } else {
            $result = $client->get($url);
        }

        $json = $result->getBody()->getContents();

        if ($data = @json_decode($json, true)) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
        } else {
            echo $data;
        }

        //print_r(['status' => $result->getStatusCode(), 'data' => @json_decode($json, true) ?: $json,]);

        return @json_decode($json, true);
    }
}