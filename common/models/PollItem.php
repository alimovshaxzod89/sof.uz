<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/**
 * Created by PhpStorm.
 * Date: 12/3/17
 * Time: 7:49 PM
 */

namespace common\models;


class PollItem
{
    public $active  = false;
    public $answer;
    public $votes   = 0;
    public $percent = 0;

    /**
     * PollItem constructor.
     * @param $answer
     */
    public function __construct($answer)
    {
        $this->answer = $answer;
        $this->active = true;
    }

    public function updatePercent($allVotes)
    {
        $this->percent = round($this->votes * 100 / $allVotes, 1);
    }

    public function upVote()
    {
        $this->votes++;
    }
}