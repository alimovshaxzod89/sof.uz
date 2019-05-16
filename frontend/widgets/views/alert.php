<?php
/**
 * Created by PhpStorm.
 * User: shavkat
 * Date: 12/30/17
 * Time: 3:03 PM
 */
$session = \Yii::$app->session;
?>
<?php foreach ($messages as $type => $items): ?>
    <div class="alert alert-<?= $type ?> mt-2">
        <a href="#" onclick="$(this).parent().slideUp();return false;" class="alert-box-close" style="float: right;">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12">
                <line x1="1" y1="11" x2="11" y2="1" stroke="#707a7c" stroke-width="2"/>
                <line x1="1" y1="1" x2="11" y2="11" stroke="#707a7c" stroke-width="2"/>
            </svg>
        </a>
        <?php foreach ($items as $i => $message): ?>
            <p>
                <?= $message ?>
            </p>
        <?php endforeach; ?>

    </div>
    <?php $session->removeFlash($type); ?>
<?php endforeach; ?>
