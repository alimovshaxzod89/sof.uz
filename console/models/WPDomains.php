<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

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
class WPDomains extends WPBase
{
    public static function tableName()
    {
        return '{{%mdm_domains}}';
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