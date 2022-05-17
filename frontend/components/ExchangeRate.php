<?php


namespace frontend\components;

use Exception;
use Yii;
use yii\base\BaseObject;

/**
 *
 * @property-read array $rates
 * @property-read array $rateCBU
 * @property-read array $rateNBU
 */
class ExchangeRate extends BaseObject
{

    public function getRates()
    {
        $cache = Yii::$app->cache;

//        $cache->delete('exchange-rate-cbu');

        $rates = $cache->getOrSet('exchange-rate-cbu', function () {

            $rates = $this->getRateCBU();

            if (!$rates)
                return false;

            $data = [];
            foreach ($rates as $item) {
                if (in_array($item['Ccy'], ['USD', 'RUB', 'EUR'])) {
                    $data[$item['Ccy']] = ['Rate' => $item['Rate'], 'Diff' => $item['Diff']];
                }
            }

            return $data;

        }, 3600);

        return $rates;
    }

    public function getRatesAnother()
    {

        $cache = Yii::$app->cache;

        $cache->delete('exchange-rate-cbu');

        $cbu = $cache->getOrSet('exchange-rate-cbu', function () {

            return $this->getRateCBU();

        }, 3600);

        $nbu = $cache->getOrSet('exchange-rate-nbu', function () {

            return $this->getRateNBU();

        }, 3600);

        return [
            'cbu' => $cbu,
            'nbu' => $nbu
        ];
    }

    public function getRateCBU()
    {

        try {
            $url = 'https://cbu.uz/oz/arkhiv-kursov-valyut/json/';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response_json = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response_json, true);

        } catch (Exception $e) {
            $response = [];
        }


        if (!$response)
            return null;

        try {
            return $response;
        } catch (Exception $e) {
            return null;
        }

    }

    public function getRateNBU()
    {
        try {
            $url = 'https://nbu.uz/uz/exchange-rates/json/';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response_json = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response_json, true);

        } catch (Exception $e) {
            $response = [];
        }

        if (!$response)
            return null;

        try {
            return $this->parseFromNBU($response);
        } catch (Exception $e) {
            return null;
        }

    }


    private function parseFromCBU($rate)
    {
        $data = [];
        foreach ($rate as $item) {
            if ($item['Ccy'] === 'USD') {
                $data['usd'] = $item['Rate'];
            } elseif ($item['Ccy'] === 'RUB') {
                $data['rub'] = $item['Rate'];
            } elseif ($item['Ccy'] === 'EUR') {
                $data['eur'] = $item['Rate'];
            }
        }
        if (isset($t->image)) {
            $data['url'] = 'background:url(' . Yii::getAlias('@fronted_domain') . '/' . $t->image . ')';
        } else {
            $data['url'] = '';
        }

        $data['name'] = Yii::t('template', 'O\'zbekiston Respublikasi Markaziy Banki');

        return $data;

    }

    private function parseFromNBU($rate)
    {
        $data = [];
        foreach ($rate as $item) {
            if ($item['code'] === 'USD') {
                $data['usd'] = $item['cb_price'];
            } elseif ($item['code'] === 'RUB') {
                $data['rub'] = $item['cb_price'];
            } elseif ($item['code'] === 'EUR') {
                $data['eur'] = $item['cb_price'];
            }
        }

        $t = RateImg::find()->where(['type' => 1])->orderBy(['id' => SORT_DESC])->one();

        if (isset($t->image)) {
            $data['url'] = 'background:url(' . Yii::getAlias('@fronted_domain') . '/' . $t->image . ')';
        } else {
            $data['url'] = '';
        }

        $data['name'] = Yii::t('template', 'O\'zbekiston Milliy Banki');

        return $data;
    }

}
