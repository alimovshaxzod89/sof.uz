<?php

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