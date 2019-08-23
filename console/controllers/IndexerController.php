<?php

namespace console\controllers;

use Cmfcmf\OpenWeatherMap;
use common\components\Config;
use common\models\Ad;
use common\models\Currency;
use common\models\Place;
use common\models\Post;
use common\models\Stat;
use common\models\SystemMessage;
use common\models\Tag;
use common\models\Weather;
use MongoDB\BSON\Timestamp;
use Yii;
use yii\console\Controller;

class IndexerController extends Controller
{

    /**
     * every 30 seconds
     */
    public function actionSuperFast($final = 1)
    {
        Post::publishAutoPublishPosts($final);
    }

    /**
     * every 1 minutes
     */
    public function actionVeryFast()
    {

    }

    /**
     * every 5 minutes
     */
    public function actionFast()
    {
        Ad::reindexStatuses();
        Place::reindexStatuses();

        Stat::indexPostViewsReset();
        Stat::indexPostViewsAll();
        Stat::indexPostViewsToday();
        Stat::indexPostViewsL3D();
        Stat::indexPostViewsL7D();
        Stat::indexPostViewsL30D();
        Tag::indexTrendingTags();

        Stat::indexAdClicksAll();
        Stat::indexAdViewsAll();
    }

    /**
     * every half hour
     */
    public function actionNormal()
    {
        Tag::indexAllTags();
        SitemapController::generate();

        //$this->actionWeather();
        //$this->actionCurrency();
    }

    /**
     * every day
     */
    public function actionDaily()
    {
    }

    /**
     * every 2 hour
     */
    public function actionSlow()
    {
        $this->actionCleanPosts();
    }


    public function actionWeatherClean()
    {
        Weather::deleteAll();
    }

    public function actionWeather()
    {
        $cities = [
            'Tashkent'  => ['lat' => '41.310586', 'lon' => '69.280214'],
            'Andijon'   => ['lat' => '40.773563', 'lon' => '72.359219'],
            'Sirdaryo'  => ['lat' => '40.491509', 'lon' => '68.781077'],
            'Buxoro'    => ['lat' => '39.772547', 'lon' => '64.422712'],
            'Fargona'   => ['lat' => '40.379023', 'lon' => '71.797199'],
            'Jizzax'    => ['lat' => '40.144018', 'lon' => '67.830791'],
            'Namangan'  => ['lat' => '40.999743', 'lon' => '71.669912'],
            'Navoiy'    => ['lat' => '40.103922', 'lon' => '65.368833'],
            'Nukus'     => ['lat' => '42.461891', 'lon' => '59.616631'],
            'Samarqand' => ['lat' => '39.650153', 'lon' => '66.965618'],
            'Urganch'   => ['lat' => '41.549997', 'lon' => '60.633373'],
            'Termiz'    => ['lat' => '37.235291', 'lon' => '67.283192'],
            'Qarshi'    => ['lat' => '38.848574', 'lon' => '65.793171'],
        ];

        $owm = new OpenWeatherMap('7036ec8641edbec8a9a8ff0828395e6f');
        $cc  = 0;
        $wd  = 0;
        $wc  = 0;
        $wu  = 0;
        foreach ($cities as $c => $city) {
            $weathers[] = $owm->getRawDailyForecastData(
                $city,
                'metric',
                'en',
                getenv('OPEN_WEATHER_MAP_API_KEY'),
                'json',
                6
            );
            $cc++;
            if (count($weathers)) {
                foreach ($weathers as $k => $weather) {
                    $weather = @json_decode($weather, true);
                    $data    = [];
                    if (count($weather['list'])):
                        foreach ($weather['list'] as $item) {

                            $date = new \DateTime();
                            $date->setTimestamp($item['dt']);
                            $date->setTime(0, 0, 0);
                            // echo $date->format('d/m/y') . PHP_EOL;
                            $wd++;
                            $data['day']         = $date->format('U');
                            $data['cityName']    = $weather['city']['name'];
                            $data['cityId']      = $weather['city']['id'];
                            $data['temperature'] = $item['temp'];
                            $data['date']        = $item['dt'];
                            $data['status']      = $item['weather'][0]['main'];
                            $data['info']        = $item['weather'][0]['description'];

                            if ($exists = Weather::findOne(['cityId' => $weather['city']['id'], 'day' => $data['day']])) {
                                $wu++;
                                $exists->updateAttributes([
                                                              'temperature' => $data['temperature'],
                                                              'status'      => $data['status'],
                                                              'info'        => $data['info'],
                                                          ]);
                            } else {
                                $wc++;
                                $model = new Weather($data);
                                if ($model->save()) ;
                            }
                        }
                    endif;
                }

            }
        }
        echo $cc . 'city ' . $wd / 7 . ' days weather ' . round($wc / 7) . ' created ' . round($wu / 7) . ' updated saved' . PHP_EOL;
    }

    public function actionCurrencyFix()
    {
        $data = [
            'USD_ch' => -3.33,
            'EUR_ch' => -76.78,
            'RUB_ch' => -0.71,
            'GBP_ch' => -60.33,
            'CNY_ch' => -0.93,
            'JPY_ch' => -0.56,
            'KRW_ch' => -0.02,
            'CHF_ch' => -84.79,
            'KZT_ch' => -0.20,
        ];
        if ($exist = Currency::find()->where(['date' => '12.12.2017'])->one()) {
            $exist->updateAttributes($data);
        }
    }

    public function actionCurrency()
    {
        $currencies = Currency::getCurrencies();
        if ($data = simplexml_load_string(@file_get_contents('http://cbu.uz/uz/arkhiv-kursov-valyut/xml/'))) {
            if ($data = @json_decode(json_encode($data), true)) {
                $rates = [];
                foreach ($data['CcyNtry'] as $index => $symbol) {
                    if (in_array($symbol['Ccy'], $currencies)) {
                        $rates['date']         = $symbol['date'];
                        $rates[$symbol['Ccy']] = round(floatval($symbol['Rate']), 2);
                    }
                }
                if (count($rates)) {


                    if ($exist = Currency::findOne(['date' => $rates['date']])) {
                        if ($last = Currency::find()->andWhere(['date' => ['$ne' => $exist->date]])->orderBy(['created_at' => SORT_DESC])->limit(1)->one()) {
                            foreach ($currencies as $currency) {
                                $rates[$currency . '_ch'] = $rates[$currency] - $last->$currency;
                            }
                        }

                        $exist->updateAttributes($rates);
                        echo 'Available currency updated' . PHP_EOL;
                    } else {
                        if ($last = Currency::find()->orderBy(['created_at' => SORT_DESC])->limit(1)->one()) {
                            foreach ($currencies as $currency) {
                                $rates[$currency . '_ch'] = $rates[$currency] - $last->$currency;
                            }
                        }
                        $rate = new Currency($rates);
                        $rate->save();
                        echo 'New currency saved' . PHP_EOL;
                    }
                }
            }
        }

    }

    public function actionCleanPosts()
    {
        $date = new \DateTime();
        $time = (int)$date->format('U') - 30 * 24 * 3600;
        $posts = Post::find()
                    ->where([
                                'created_at' => ['$lt' => new Timestamp(1, $time)],
                                'status'     => Post::STATUS_DRAFT,
                                'title'      => ['$in' => [null, '']],
                            ])
                    ->all();

        foreach ($posts as $post) {
            $post->delete();
            echo $post->getId() . PHP_EOL;
        }
    }

    public function actionCreateIndex()
    {
        $collection = Post::getCollection();
        echo $collection->createIndex(['published_on' => -1]);
        echo $collection->createIndex(['views' => -1]);
        echo $collection->createIndex(['views_today' => -1]);
        echo $collection->createIndex(['views_l3d' => -1]);
        echo $collection->createIndex(['views_l7d' => -1]);
        echo $collection->createIndex(['views_l30d' => -1]);
        echo $collection->createIndex(['old_id' => -1]);

        echo $collection->createIndex(['status' => 1]);
        echo $collection->createIndex(['is_mobile' => 1]);
        echo $collection->createIndex(['is_main' => 1]);
        echo $collection->createIndex(['has_russian' => 1]);
        echo $collection->createIndex(['has_uzbek' => 1]);
        echo $collection->createIndex(['_domain' => 1]);
        echo $collection->createIndex(['_tags' => 1]);
        echo $collection->createIndex(['_categories' => 1]);

        $collection = Tag::getCollection();
        echo $collection->createIndex(['count' => -1]);
        echo $collection->createIndex(['count_l5d' => -1]);
        echo $collection->createIndex(['name_uz' => 1]);
        echo $collection->createIndex(['name_ru' => 1]);
        echo $collection->createIndex(['name_oz' => 1]);

        $collection = Ad::getCollection();
        echo $collection->createIndex(['views' => -1]);
        echo $collection->createIndex(['clicks' => -1]);
        echo $collection->createIndex(['status' => -1]);

        $collection = Stat::getCollection();
        echo $collection->createIndex(['type' => 1]);
        echo $collection->createIndex(['model' => 1]);
        echo $collection->createIndex(['hour' => 1]);
        echo $collection->createIndex(['day' => 1]);
        echo $collection->createIndex(['month' => 1]);
        echo $collection->createIndex(['year' => 1]);
        echo $collection->createIndex(['time' => 1]);


        $collection = SystemMessage::getCollection();
        echo $collection->createIndex(['category' => 1]);

        $collection = Weather::getCollection();
        echo $collection->createIndex(['day' => 1]);
        echo $collection->createIndex(['cityId' => 1]);
    }

    public function actionPostStatic($offset = 0)
    {

        foreach (Config::getLanguages() as $lang) {
            \Yii::$app->language = $lang;
            foreach (Post::find()->all() as $post) {
                $post->content = preg_replace("/http:\/\/static\.minbar\.uz\//i", Yii::getAlias('@staticUrl') . '/', $post->content);
                if ($post->save(false)) {
                    echo $post->title . PHP_EOL;
                }
            }
        }
    }

    public function actionPostUnLock()
    {
        echo Post::updateAll(['_creator' => '', '_creator_session' => '', 'locked_on' => '']);
    }

    public function actionPost($offset = 0, $isRussian = false)
    {
        foreach (Post::findAll(['status' => Post::STATUS_PUBLISHED]) as $post) {
            if ($post->save(false)) {
                echo $post->title . PHP_EOL;
            }
        }
    }

    public function actionPostMobile()
    {
        foreach (Post::findAll(['status' => Post::STATUS_PUBLISHED]) as $post) {
            $post->prepareMobilePost();
            $post->updateAttributes([
                                        'mobile_image'  => $post->mobile_image,
                                        'gallery_items' => $post->gallery_items,
                                    ]);
        }
    }
}
