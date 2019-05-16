<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use yii\authclient\widgets\AuthChoice;

/**
 * Created by PhpStorm.
 * Date: 11/27/17
 * Time: 6:19 PM
 */
$authAuthChoice = AuthChoice::begin([
                                        'baseAuthUrl' => ['site/auth'],
                                    ]); ?>
    <ul class="list-inline">
        <?php foreach ($authAuthChoice->getClients() as $client): ?>
            <li><?= $authAuthChoice->clientLink($client) ?></li>
        <?php endforeach; ?>
    </ul>
<?php AuthChoice::end(); ?>