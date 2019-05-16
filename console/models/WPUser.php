<?php

namespace console\models;

use common\models\User;

/**
 * Class WPUser
 * @property string ID
 * @property string user_login
 * @property string user_pass
 * @property string user_nicename
 * @property string user_email
 * @property string user_url
 * @property string user_registered
 * @property string user_activation_key
 * @property string user_status
 * @property string display_name
 */
class WPUser extends WPBase
{
    public static function tableName()
    {
        return '{{%users}}';
    }

    public static function convert()
    {
        if (function_exists('get_users')) {
            $users = get_users();
            foreach ($users as $user) {
                /* @var $user \WP_User */

                $new = new User([
                                    'scenario'     => User::SCENARIO_INSERT,
                                    'first_name'   => $user->first_name ?: 'XXX',
                                    'last_name'    => $user->last_name ?: '',
                                    'login'        => $user->user_login,
                                    'password'     => 'random1',
                                    'confirmation' => 'random1',
                                    'bio'          => $user->description,
                                    'email'        => $user->user_email,
                                    'telephone'    => '+998998070427',
                                    '_type'        => User::TYPE_USER,
                                    '_old'         => (int)$user->ID,
                                    '_guide'       => $user->nickname,
                                    'status'       => User::STATUS_ENABLE,
                                ]);

                if ($new->save()) {
                    printf("\"%s\" user saved successful.\n", $new->first_name);
                } else {
                    var_dump($new->errors);
                }
            }
        } else {
            echo "not found function get_users.\n";
        }

        $user = new User([
                             'scenario'     => 'insert',
                             'login'        => 'admin',
                             'telephone'    => '+99890 997 91 14',
                             'email'        => 'homidjonov@gmail.com',
                             'confirmation' => 'random1',
                             'password'     => 'random1',
                             'first_name'   => 'Shavkat',
                             'last_name'    => 'Homidjanov',
                             'status'       => User::STATUS_ENABLE,
                             '_type'        => User::TYPE_ADMIN,
                         ]);
        if ($user->save()) {
            printf("\"%s\" user saved successful.\n", $user->first_name);
        }
    }
}