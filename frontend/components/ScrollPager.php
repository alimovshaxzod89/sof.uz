<?php

namespace frontend\components;


use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class ScrollPager extends LinkPager
{

    public $buttonText;
    public $emptyText;
    public $perLoad;
    public $loadParam = 'load';

    public function init()
    {
        parent::init();
        if (empty($this->buttonText)) {
            $this->buttonText = __('Load more');
        }
        if (empty($this->emptyText)) {
            $this->emptyText = __('All data loaded');
        }
        if (empty($this->perLoad)) {
            $this->perLoad = 4;
        }
        Html::removeCssClass($this->options, 'pagination');
        Html::addCssClass($this->options, 'load-button load-more');
    }

    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';// Html::a($this->emptyText, '#', ['class' => 'btn primary wide']);
        }

        $options = $this->options;
        return Html::a("<i class='loading-circle'></i> " . $this->buttonText, Url::current([$this->loadParam => $this->perLoad + $this->pagination->getPageSize()]), $options);
    }


}