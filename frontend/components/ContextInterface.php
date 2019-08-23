<?php

namespace frontend\components;

use yii\base\ViewContextInterface;
use yii\web\IdentityInterface;

interface ContextInterface extends ViewContextInterface
{
    /**
     * @return IdentityInterface
     */
    public function _user();

    /**
     * @return string|null
     */
    public function getUserId();

    /**
     * @param $name string
     * @param $default
     * @return mixed
     */
    public function get($name, $default = null);

}