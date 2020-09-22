<?php

namespace api\models\v1;

use common\models\Place as PlaceModel;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;


class Place extends PlaceModel
{
    public function getAds($device = false, $width = false, $os = false)
    {
        $ad = Ad::getBanner($this, $os);

        if ($ad == false) {
            throw new NotFoundHttpException(__('Advertises not found.'));
        }

        $content = null;
        $url = null;

        $content = null;

        if ($device == 'mobile' || $this->slug == 'mobile') {
            if ($ad->type == Ad::TYPE_IMAGE) {
                if ($url = $ad->getMobileImageUrl()) {
                    $content = Html::img($url, $this->slug != 'mobile' ? ['style' => 'display:none', 'onload' => '$(this).fadeIn()'] : ['style' => 'width:100%;margin:0 auto;']);
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
                $content = Html::img($ad->getDesktopImageUrl(), $this->slug != 'mobile' ? ['style' => 'display:none', 'onload' => '$(this).fadeIn()'] : []);
            } else {
                $content = $ad->code;
            }
        }

        return [
            'type' => $ad->type,
            'content' => $ad->url && $ad->type == Ad::TYPE_IMAGE ? Html::a($content, $ad->url, ['target' => '_blank']) : $content,
            'id' => $ad->id,
        ];

    }

    public function fields()
    {
        return [
            'id',
        ];
    }
}
