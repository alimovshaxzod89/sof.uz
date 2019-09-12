<?php

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
<?php if ($fb || $tw || $yt || $ig || $tg): ?>
    <div class="social-bar">
        <?php if ($fb): ?>
            <a href="<?= $fb ?>" target="_blank">
                <i class="mdi mdi-facebook" style="color: #3b5998;"></i>
                <span class="hidden-xs hidden-sm">Facebook</span>
            </a>
        <?php endif; ?>
        <?php if ($tw): ?>
            <a href="<?= $tw ?>" target="_blank">
                <i class="mdi mdi-twitter" style="color: #1da1f2;"></i>
                <span class="hidden-xs hidden-sm">Twitter</span>
            </a>
        <?php endif; ?>
        <?php if ($ig): ?>
            <a href="<?= $ig ?>" target="_blank">
                <i class="mdi mdi-instagram" style="color: #e1306c;"></i>
                <span class="hidden-xs hidden-sm">Instagram</span>
            </a>
        <?php endif; ?>
        <?php if ($yt): ?>
            <a href="<?= $yt ?>" target="_blank">
                <i class="mdi mdi-youtube-play" style="color: #c61d23;"></i>
                <span class="hidden-xs hidden-sm">Youtube</span>
            </a>
        <?php endif; ?>
        <?php if ($tg): ?>
            <a href="<?= $tg ?>" target="_blank">
                <i class="mdi mdi-telegram" style="color: #0088cc;"></i>
                <span class="hidden-xs hidden-sm">Telegram</span>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<footer class="site-footer">
    <div class="container">
        <nav class="footer-menu">
            <?= \common\models\Page::getStaticBlock('footer-menu') ?>
        </nav>

        <div class="site-info">
            <?= __('© 2018 Magsy - Magazine &amp; Blog Theme. All rights reserved') ?>
        </div>
    </div>
</footer>