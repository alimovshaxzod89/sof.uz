<?php

use common\components\Config;

/**
 * @var $this \frontend\components\View
 */
$fb = Config::get(Config::CONFIG_SOCIAL_FACEBOOK_LINK);
$tw = Config::get(Config::CONFIG_SOCIAL_TWITTER_LINK);
$yt = Config::get(Config::CONFIG_SOCIAL_YOUTUBE_LINK);
$tg = Config::get(Config::CONFIG_SOCIAL_TELEGRAM_LINK);
$ig = Config::get(Config::CONFIG_SOCIAL_INSTAGRAM_LINK);
?>
<?php if ($fb || $tw || $yt || $tg || $ig): ?>
    <div class="widget widget_magsy_social_widget">
        <h5 class="widget-title"><?= __('Social Links') ?></h5>
        <div class="links">
            <?php if ($fb): ?>
                <a style="background-color: #3b5998;" href="<?= $fb ?>" target="_blank">
                    <i class="mdi mdi-facebook"></i>
                    <span>Facebook</span>
                </a>
            <?php endif; ?>
            <?php if ($tw): ?>
                <a style="background-color: #1da1f2;" href="<?= $tw ?>" target="_blank">
                    <i class="mdi mdi-twitter"></i>
                    <span>Twitter</span>
                </a>
            <?php endif; ?>
            <?php if ($ig): ?>
                <a style="background-color: #e1306c;" href="<?= $ig ?>" target="_blank">
                    <i class="mdi mdi-instagram"></i>
                    <span>Instagram</span>
                </a>
            <?php endif; ?>
            <?php if ($yt): ?>
                <a style="background-color: #c61d23;" href="<?= $yt ?>" target="_blank">
                    <i class="mdi mdi-youtube-play"></i>
                    <span>Youtube</span>
                </a>
            <?php endif; ?>
            <?php if ($tg): ?>
                <a style="background-color: #08c;" href="<?= $tg ?>" target="_blank">
                    <i class="mdi mdi-telegram"></i>
                    <span>Telegram</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>