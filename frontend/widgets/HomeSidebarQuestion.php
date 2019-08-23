<?php

namespace frontend\widgets;

class HomeSidebarQuestion extends BaseWidget
{
    public function init()
    {
        $this->emptyText = __('Questions not found');
    }

    public function run()
    {
        return $this->render('homeSidebarQuestion', [
            'questions' => []
        ]);
    }
}