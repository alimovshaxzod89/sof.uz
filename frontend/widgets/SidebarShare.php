<?php

namespace frontend\widgets;

class SidebarShare extends BaseWidget
{
    public function init()
    {
    }

    public function run()
    {
        return $this->render('sidebarShare');
    }
}