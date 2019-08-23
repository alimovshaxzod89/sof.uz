<?php

namespace frontend\widgets;

use common\models\Poll;
use yii\base\Widget;

class SidebarPoll extends Widget
{
    const MODE_NEW    = 'new';
    const MODE_RANDOM = 'random';

    public $mode = self::MODE_NEW;
    /** @var  Poll */
    public $poll;
    public $ajax;
    public $container = true;

    public function run()
    {
        if ($this->poll == null) {
            if ($this->mode == self::MODE_NEW) {
                $this->poll = Poll::find()
                                  ->where(['status' => Poll::STATUS_ENABLE])
                                  ->orderBy(['created_at' => SORT_DESC])
                                  ->one();
            }
            if ($this->mode == self::MODE_RANDOM) {
                $polls = Poll::find()->where(['status' => Poll::STATUS_ENABLE])->all();
                (count($polls)) ? $this->poll = $polls[array_rand($polls)] : [];
            }
        }

        return $this->render('sidebarPoll', [
            'poll'      => $this->poll,
        ]);
    }
}