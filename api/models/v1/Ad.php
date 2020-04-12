<?php

namespace api\models\v1;

use common\models\Ad as AdModel;
use common\models\Stat;
use Imagine\Image\ManipulatorInterface;
use MongoDB\BSON\ObjectId;


class Ad extends AdModel
{
    public function getFullImageUrl($img = false, $width)
    {
        $img = $img ?: $this->image;
        return self::getCropImage($img, $width, null, ManipulatorInterface::THUMBNAIL_OUTBOUND);
    }

    private static $_indexes;

    /**
     * @param Place $place
     * @return self
     */
    public static function getBanner(Place $place, $os)
    {
        $ids = $place->_ads;

        if (is_array($ids) && count($ids)) {
            $ids = array_filter(array_map(function ($id) {
                return new ObjectId($id);
            }, $ids));

            $ads = self::find()
                ->where(['_id' => ['$in' => array_values($ids)]])
                ->andWhere(['status' => self::STATUS_ENABLE])
                ->indexBy('id')
                ->all();

            if (count($ads)) {
                $data = [];
                $allAds = [];
                $maxWeight = 0;
                $maxId = 0;


                foreach ($ads as $ad) {
                    if (is_array($ad->platforms) && count($ad->platforms) > 0 && $os) {
                        if (!in_array($os, $ad->platforms)) {
                            continue;
                        }
                    }
                    $allAds[$ad->getId()] = $ad;
                    $weight = $place->getAddPercent($ad);

                    $data = array_merge($data, array_fill(0, $weight, $ad->getId()));

                    if ($weight > $maxWeight) {
                        $weight = $maxWeight;
                        $maxId = $ad->getId();
                    }
                }

                if (count($data)) {
                    if ($place->mode == Place::MODE_RAND) {
                        $index = rand(0, count($data) - 1);
                        $ad = $allAds[$data[$index]];
                    } else {
                        if ($maxId) {
                            $ad = $allAds[$maxId];
                        }
                    }
                    Stat::registerAdView($ad);

                    return $ad;
                }
            }
        }

        return false;
    }

    public function fields()
    {
        return [
            'id',
        ];
    }
}
