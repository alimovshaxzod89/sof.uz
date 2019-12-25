<?php

namespace api\models\v1;

use common\models\Place as PlaceModel;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;


class Place extends PlaceModel
{
    public function getAds($device = false, $width = false, $mobile = false)
    {
        $ad = Ad::getBanner($this);

        if ($ad == false) {
            throw new NotFoundHttpException(__('Advertises not found.'));
        }

        $content = null;
        $url     = null;

        if ($mobile) {
            $url = $ad->getMobileImageUrl();

            return [
                'type'    => $ad->type,
                'link'    => $ad->url,
                'image'   => $url ? $url : $ad->getDesktopImageUrl(),
                'content' => $ad->code_mobile ? $ad->code_mobile : $ad->code,
                'id'      => $ad->id,
            ];

        } else {
            $content = null;

            if ($device == 'mobile') {
                if ($ad->type == Ad::TYPE_IMAGE) {
                    if ($url = $ad->getMobileImageUrl()) {
                        $content = Html::img($url, ['style' => 'display:none', 'onload' => '$(this).fadeIn()']);
                    } else {
                        $content = Html::img($ad->getDesktopImageUrl());
                    }
                } else {
                    if ($ad->code_mobile) {
                        $content = $ad->code_mobile;
                    } else {
                        $content = $ad->code;
                    }
                }
            } else {
                if ($ad->type == Ad::TYPE_IMAGE) {
                    $content = Html::img($ad->getDesktopImageUrl(), ['style' => 'display:none', 'onload' => '$(this).fadeIn()']);
                } else {
                    $content = $ad->code;
                }
            }

            return [
                'type'    => $ad->type,
                'content' => $ad->url && $ad->type == Ad::TYPE_IMAGE ? Html::a($content, $ad->url, ['target' => '_blank']) : $content,
                'id'      => $ad->id,
            ];
        }

    }

    public function fields()
    {
        return [
            'id',
        ];
    }
}
