<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/11/17
 * Time: 9:59 PM
 */

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