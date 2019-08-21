<?php

use common\models\Admin;

class m170926_085313_add_admin_user extends \yii\mongodb\Migration
{
    public function up()
    {
        $admin = new Admin([
                               'scenario'     => Admin::SCENARIO_INSERT,
                               'login'        => 'admin',
                               'telephone'    => '+998909979114',
                               'email'        => 'admin@activemedia.uz',
                               'confirmation' => 'random1',
                               'password'     => 'random1',
                               'fullname'     => 'Shavkat',
                               'status'       => Admin::STATUS_ENABLE,
                           ]
        );

        $admin->save();
    }

    public function down()
    {
        Admin::deleteAll(['login' => 'admin']);
    }
}
