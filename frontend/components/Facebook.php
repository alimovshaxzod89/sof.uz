<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

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
