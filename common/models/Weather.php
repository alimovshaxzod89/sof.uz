<?php
/**
 * Created by PhpStorm.
 * User: lisa
 * Date: 12/12/17
 * Time: 9:17 PM
 */

namespace common\models;

use yii\helpers\Inflector;

/**
 * Class Weather
 * @package common\models
 * @property string          id
 * @property ObjectId        _id
 * @property string          cityId
 * @property string          cityName
 * @property string          status
 * @property array           temperature
 * @property string          position
 * @property string          info
 * @property string          day
 * @property Timestamp date
 * @property Timestamp lastUpdate
 * @property Timestamp $created_at
 * @property Timestamp $updated_at
 */
class Weather extends MongoModel
{
    const STATUS_LIGHT_SNOW       = 'light snow';
    const STATUS_SKY_IS_CLEAR     = 'sky is clear';
    const STATUS_FEW_CLOUDS       = 'few clouds';
    const STATUS_SCATTERED_CLOUDS = 'scattered clouds';
    const STATUS_LIGHT_RAIN       = 'light rain';

    const CITY_TASHKENT  = 1484839;
    const CITY_ANDIJAN   = 1514588;
    const CITY_BUKHARA   = 1217662;
    const CITY_FERGHANA  = 1514019;
    const CITY_DJIZZAKH  = 1513886;
    const CITY_NAMANGAN  = 1513157;
    const CITY_NAVOI     = 1538229;
    const CITY_NUKUS     = 829930;
    const CITY_SAMARKAND = 1216265;
    const CITY_URGANCH   = 1512473;
    const CITY_TERMEZ    = 1215957;
    const CITY_SYRDARYA  = 1512770;
    const CITY_KARSHI    = 1216311;

    public static function getStatusOptions()
    {
        return [
            self::STATUS_LIGHT_SNOW       => __('Light snow'),
            self::STATUS_SKY_IS_CLEAR     => __('Sky is clear'),
            self::STATUS_FEW_CLOUDS       => __('Few clouds'),
            self::STATUS_SCATTERED_CLOUDS => __('Scattered clouds'),
            self::STATUS_LIGHT_RAIN       => __('Light rain'),
        ];
    }

    public static function getCityOptions()
    {
        $cities = [
            1484839 => __('Toshkent'),
            1514588 => __('Andijon'),
            1217662 => __('Buxoro'),
            1514019 => __('Fargona'),
            1513886 => __('Jizzax'),
            1513157 => __('Namangan'),
            1538229 => __('Navoiy'),
            601294  => __('Nukus'),
            1216265 => __('Samarqand'),
            1512473 => __('Urganch'),
            1215957 => __('Termiz'),
            1513966 => __('Guliston'),
            1216311 => __('Qarshi'),
        ];
        asort($cities);

        return $cities;
    }

    public function getStatus()
    {
        $arr = self::getStatusOptions();
        return isset($arr[$this->status]) ? $arr[$this->status] : $this->status;
    }

    public static function collectionName()
    {
        return 'weather';
    }

    public function rules()
    {
        return [
            [['cityId', 'cityName', 'status', 'temperature', 'info', 'date', 'lastUpdate', 'day'], 'safe'],
        ];
    }

    public function attributes()
    {
        return [
            '_id',
            'cityId',
            'cityName',
            'status',
            'temperature',
            'info',
            'date',
            'lastUpdate',
            'created_at',
            'updated_at',
            'day',
        ];
    }

    public function getTemp()
    {
        $temps = $this->temperature;
        $date  = new \DateTime();
        $date->setTimestamp($this->date);

        $temp = $temps['max'];
        if (false && Weather::getCurrentDay() == $date->format('d/m/y')) {
            $time = date("H");
            switch ($time) {
                case $time >= 4 && $time < 10:
                    $temp = $temps['morn'];
                    break;
                case $time >= 10 && $time < 16:
                    $temp = $temps['day'];
                    break;
                case $time >= 16 && $time < 22:
                    $temp = $temps['eve'];
                    break;
                case $time >= 22 && $time <= 24 || $time < 4:
                    $temp = $temps['night'];
                    break;
                default:
                    $temp = $temps['day'];
            }
        }

        if (round($temp) > 0) {
            return '+' . round($temp);
        } else {
            return round($temp);
        }
    }

    public function getIcon()
    {
        $time = date('H');
        if (true || $time < 18 && $time > 6) {
            $suffix = '_day.svg';
        } else {
            $suffix = '_night.svg';
        }
        $icon = Inflector::slug($this->info, '_');
        return $icon . $suffix;
    }

    private static $_currentDay;

    public static function getCurrentDay()
    {
        if (!self::$_currentDay) {
            $date = new \DateTime('now');
            $date->setTime(0, 0, 0);
            self::$_currentDay = $date->format('U');
        }

        return self::$_currentDay;
    }
}