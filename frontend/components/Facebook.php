<?php

namespace frontend\components;

use ymaker\social\share\base\AbstractDriver;

class Facebook extends AbstractDriver
{

    /**
     * @inheritdoc
     */
    protected function processShareData()
    {
        $this->url         = static::encodeData($this->url);
        $this->description = static::encodeData($this->description);
    }

    protected function buildLink()
    {
        return parent::buildLink();
        // TODO: Implement buildLink() method.
    }
}
