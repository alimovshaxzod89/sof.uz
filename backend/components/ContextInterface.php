<?php

namespace backend\components;

use yii\base\ViewContextInterface;
use yii\web\IdentityInterface;

interface ContextInterface extends ViewContextInterface
{
    /**
     * @return IdentityInterface
     */
    public function _user();

}