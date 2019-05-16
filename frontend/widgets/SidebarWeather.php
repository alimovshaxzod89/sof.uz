<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 12/13/17
 * Time: 1:35 AM
 */

namespace frontend\widgets;

use common\models\Weather as OWM;
use common\models\Weather;
use DateInterval;
use Yii;
use yii\base\Widget;
use yii\web\Cookie;

class SidebarWeather extends Widget
{
    const COOKIE_KEY = '_city';
    public $city;
    public $day;
    public $nextDay;
    public $prevDay;
    public $weathers;

    public function init()
    {
        $cookie = Yii::$app->request->cookies->get(self::COOKIE_KEY);

        $this->city = intval(Yii::$app->request->get('wc', $cookie ? $cookie->value : OWM::CITY_TASHKENT));

        if (!array_key_exists($this->city, Weather::getCityOptions())) {
            $this->city = Weather::CITY_TASHKENT;
        }

        Yii::$app->response->cookies->add(new Cookie([
                                                         'name'     => self::COOKIE_KEY,
                                                         'value'    => $this->city,
                                                         'httpOnly' => true,
                                                         'expire'   => time() + 60 * 24 * 3600,
                                                     ]));

        $this->day = intval(Yii::$app->request->get('wd', 0));

        if ($this->day > 6) {
            $this->day = 0;
        } elseif ($this->day < 0) {
            $this->day = 6;
        }

        $date = new \DateTime('now');
        $date->setTime(0,0,0);
        $date->add(new DateInterval("P{$this->day}D"));

        $this->weathers = OWM::find()
                             ->where([
                                         'cityId' => $this->city,
                                         'day'    => [
                                             '$gte' => $date->format('U'),
                                         ],
                                     ])
                             ->orderBy(['date' => SORT_ASC])
                             ->limit(2)
                             ->all();

        parent::init();
    }

    public function run()
    {
        return $this->render('sidebarWeather', [
            'weathers' => $this->weathers,
        ]);
    }
}