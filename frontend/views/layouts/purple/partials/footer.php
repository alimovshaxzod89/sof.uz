<?php

use yii\helpers\Url;
use common\components\Config;

/**
 * @var $this frontend\components\View
 */
$fb = Config::get(Config::CONFIG_SOCIAL_FACEBOOK_LINK);
$tw = Config::get(Config::CONFIG_SOCIAL_TWITTER_LINK);
$yt = Config::get(Config::CONFIG_SOCIAL_YOUTUBE_LINK);
$ig = Config::get(Config::CONFIG_SOCIAL_INSTAGRAM_LINK);
$tg = Config::get(Config::CONFIG_SOCIAL_TELEGRAM_LINK);
?>
<div class="nav_foot">
    <div class="foot_link">
        <div class="nav-foot">

            <a href="<?= Url::to(['/page/sayt-haqida']) ?>" class="link" style="color: white;"><?= __('Сайт ҳақида') ?></a> <span class="break">|</span>
<!--            <a href="#" class="nav_link" style="color: white;">Алоқа</a> <span class="break">|</span>-->
            <a href="<?= Url::to(['/page/reklama']) ?>" class="link" style="color: white;"><?= __('Реклама') ?></a>

        </div>
    </div>
    <a class="plus-icon" id="myBtn" href="<?= Url::to(['/page/18plus']) ?>"></a>
</div>

<footer class="main_footer">
    <div class="text_footer"><?= __('Сайт материалларидан тўлиқ ёки қисман фойдаланилганда веб-сайт манзили кўрсатилиши
        шарт.​ ЎзМАА интернет-ОАВ гувоҳномаси рақами: 1124 Расмий хабарларда ва блогларда кетган маълумот -
        мақолаларга муаллифлар ва блогерларнинг ўзлари масъулдирлар.') ?>
    </div>
    <div class="icons_footer">
        <?php if ($yt): ?>
        <a href="<?= $yt ?>" target="_blank">
            <div class="youtube"></div>
        </a>
        <?php endif; ?>

        <?php if ($tg): ?>
            <a href="<?= $tg ?>" target="_blank">
                <div class="telegram"></div>
            </a>
        <?php endif; ?>

        <?php if ($fb): ?>
            <a href="<?= $fb ?>" target="_blank">
                <div class="facebook"></div>
            </a>
        <?php endif; ?>

        <?php if ($tw): ?>
            <a href="<?= $tw ?>" target="_blank">
                <div class="twitter"></div>
            </a>
        <?php endif; ?>

        <?php if ($ig): ?>
            <a href="<?= $ig ?>" target="_blank">
                <div class="instagram"></div>
            </a>
        <?php endif; ?>
    </div>
</footer>