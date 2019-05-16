<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace backend\components;


use common\models\Admin;
use Yii;
use yii\base\ActionFilter;
use yii\web\ForbiddenHttpException;

class FilterAccessControl extends ActionFilter
{
    public $user            = 'user';
    public $except          = ['login', 'error', 'logout', 'reset', 'cache'];
    public $exceptResources = [
        'file-storage/upload',
        'file-storage/delete',
        'dashboard/index',
        'dashboard/login',
        'dashboard/profile',
        'dashboard/reset',
        'dashboard/logout',
    ];

    protected function getFullActionName()
    {
        return Yii::$app->controller->id . "/" . Yii::$app->controller->action->id;
    }

    public function beforeAction($action)
    {
        /**
         * @var $user Admin
         */
        if ($user = Yii::$app->user->identity) {
            $action = $this->getFullActionName();
            if (in_array($action, $this->exceptResources) || $user->canAccessToResource($action)) {
                return parent::beforeAction($action);
            }
        }

        return $this->denyAccess();

    }

    protected function denyAccess()
    {

        if (Yii::$app->user->getIsGuest()) {
            Yii::$app->user->loginRequired();
        } else {
            throw new ForbiddenHttpException(__('You are not allowed to perform this action.'));
        }

        return false;
    }
}