<?php

namespace frontend\widgets;

class LastAudioList extends BaseWidget
{
    public $player    = true;
    public $items;
    public $container = '';
    public $title     = null;
    public $all       = false;

    public function run()
    {
        $this->getView()->registerJs('$("' . $this->container . '").audioPlayer("init");');

        return $this->render('lastAudioList', [
            'items'  => $this->items,
            'player' => $this->player,
            'title'  => $this->title,
            'all'    => $this->all,
        ]);
    }
}