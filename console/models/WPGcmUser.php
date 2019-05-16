<?php

namespace console\models;

use common\models\GcmUser;

/**
 * Class GcmUser
 *
 * @property string $_id
 * @property string $gcm_regid
 * @property string $fail
 * @property string $success
 * @property string $created_at
 *
 * @package common\models
 */
class WPGcmUser extends WPBase
{
    public static function tableName()
    {
        return '{{%gcm_users}}';
    }

    public static function convert()
    {
        $all = self::find()->all();

        foreach ($all as $g) {
            $new = new GcmUser([
                                   'scenario'    => 'insert',
                                   'gcm_regid'  => $g->gcm_regid,
                                   'fail'       => $g->fail,
                                   'success'    => $g->success,
                                   'created_at' => $g->created_at,
                               ]);
            if ($new->save()) {
                printf("Saved %s user successfully.\n", $new->getId());
            }
        }
    }
}