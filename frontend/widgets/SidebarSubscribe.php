<?php

namespace frontend\widgets;

class SidebarSubscribe extends BaseWidget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('sidebarSubscribe');
    }
}