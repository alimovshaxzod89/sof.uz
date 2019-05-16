<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/* @var $this View */
/* @var $name string */
/* @var $message string */

/* @var $exception Exception */

require_once __DIR__ . '/../../components/Browser.php';
use common\components\Browser;
use frontend\components\View;

$browser     = new Browser();
$minPlatform = [
    Browser::PLATFORM_ANDROID => [
        Browser::BROWSER_CHROME     => 'https://play.google.com/store/apps/details?id=com.android.chrome',
        Browser::BROWSER_FIREFOX    => 'https://play.google.com/store/apps/details?id=org.mozilla.firefox',
        Browser::BROWSER_UCBROWSER  => 'https://play.google.com/store/apps/details?id=com.UCMobile.intl',
        Browser::BROWSER_SAMSUNG    => 'https://play.google.com/store/apps/details?id=com.sec.android.app.sbrowser',
        Browser::BROWSER_YANDEX     => 'https://play.google.com/store/apps/details?id=com.yandex.browser',
        Browser::BROWSER_OPERA_MINI => 'https://play.google.com/store/apps/details?id=com.opera.mini.native',
        Browser::BROWSER_OPERA      => 'https://play.google.com/store/apps/details?id=com.opera.browser',
    ],


    Browser::PLATFORM_WINDOWS => [
        Browser::BROWSER_CHROME  => 'https://www.google.com/chrome/browser/desktop/index.html',
        Browser::BROWSER_FIREFOX => 'https://www.mozilla.org/en-US/firefox/new/',
        Browser::BROWSER_YANDEX  => 'https://browser.yandex.com/',
        Browser::BROWSER_IE      => 'https://www.microsoft.com/en-us/download/internet-explorer.aspx',
        Browser::BROWSER_EDGE    => 'https://www.microsoft.com/en-us/download/details.aspx?id=48126',
        Browser::BROWSER_OPERA   => 'http://www.opera.com/computer',
    ],


    Browser::PLATFORM_IPHONE => [
        Browser::BROWSER_CHROME    => 'https://itunes.apple.com/us/app/google-chrome/id535886823?mt=8',
        Browser::BROWSER_FIREFOX   => 'https://itunes.apple.com/us/app/firefox-web-browser/id989804926?mt=8',
        Browser::BROWSER_UCBROWSER => 'https://itunes.apple.com/us/app/uc-browser-fast-ad-block/id1048518592?mt=8',
        Browser::BROWSER_YANDEX    => 'https://itunes.apple.com/ru/app/yandex/id1050704155?l=en&mt=8',
        Browser::BROWSER_SAFARI    => 'https://itunes.apple.com/us/app/safari-to-go/id385824712?mt=8',
        Browser::BROWSER_OPERA     => 'https://itunes.apple.com/ru/app/%D0%B2%D0%B5%D0%B1-%D0%B1%D1%80%D0%B0%D1%83%D0%B7%D0%B5%D1%80-opera/id363729560?mt=8',
    ],

    Browser::PLATFORM_IPAD => [
        Browser::BROWSER_CHROME    => 'https://itunes.apple.com/us/app/google-chrome/id535886823?mt=8',
        Browser::BROWSER_FIREFOX   => 'https://itunes.apple.com/us/app/firefox-web-browser/id989804926?mt=8',
        Browser::BROWSER_UCBROWSER => 'https://itunes.apple.com/us/app/uc-browser-fast-ad-block/id1048518592?mt=8',
        Browser::BROWSER_YANDEX    => 'https://itunes.apple.com/ru/app/yandex/id1050704155?l=en&mt=8',
        Browser::BROWSER_SAFARI    => 'https://itunes.apple.com/us/app/safari-to-go/id385824712?mt=8',
        Browser::BROWSER_OPERA     => 'https://itunes.apple.com/ru/app/%D0%B2%D0%B5%D0%B1-%D0%B1%D1%80%D0%B0%D1%83%D0%B7%D0%B5%D1%80-opera/id363729560?mt=8',
    ],

    Browser::PLATFORM_APPLE => [
        Browser::BROWSER_CHROME    => 'https://itunes.apple.com/us/app/google-chrome/id535886823?mt=8',
        Browser::BROWSER_FIREFOX   => 'https://itunes.apple.com/us/app/firefox-web-browser/id989804926?mt=8',
        Browser::BROWSER_UCBROWSER => 'https://itunes.apple.com/us/app/uc-browser-fast-ad-block/id1048518592?mt=8',
        Browser::BROWSER_YANDEX    => 'https://itunes.apple.com/ru/app/yandex/id1050704155?l=en&mt=8',
        Browser::BROWSER_SAFARI    => 'https://itunes.apple.com/us/app/safari-to-go/id385824712?mt=8',
        Browser::BROWSER_OPERA     => 'https://itunes.apple.com/ru/app/%D0%B2%D0%B5%D0%B1-%D0%B1%D1%80%D0%B0%D1%83%D0%B7%D0%B5%D1%80-opera/id363729560?mt=8',
    ],

    Browser::PLATFORM_LINUX => [
        Browser::BROWSER_CHROME  => 'https://www.google.com/chrome/browser/desktop/index.html',
        Browser::BROWSER_FIREFOX => 'https://www.mozilla.org/en-US/firefox/new/',
        Browser::BROWSER_OPERA   => 'http://www.opera.com/computer',
        Browser::BROWSER_YANDEX  => 'https://browser.yandex.com/',
    ],
];
$link        = false;

if (isset($minPlatform[$browser->getPlatform()])) {
    if ($minPlatform[$browser->getPlatform()][$browser->getBrowser()]) {
        $link = $minPlatform[$browser->getPlatform()][$browser->getBrowser()];
    } else {
        if ($browser->getPlatform() == Browser::PLATFORM_ANDROID) {
            $link = $minPlatform[Browser::PLATFORM_ANDROID][Browser::BROWSER_CHROME];
        } else if (in_array($browser->getPlatform(), [Browser::PLATFORM_IPHONE, Browser::PLATFORM_IPAD, Browser::PLATFORM_APPLE])) {
            $link = $minPlatform[Browser::PLATFORM_IPHONE][Browser::BROWSER_SAFARI];
        } else if (in_array($browser->getPlatform(), [Browser::PLATFORM_WINDOWS, Browser::PLATFORM_WINDOWS_CE])) {
            $link = $minPlatform[Browser::PLATFORM_WINDOWS][Browser::BROWSER_CHROME];
        } else if (in_array($browser->getPlatform(), [Browser::PLATFORM_LINUX])) {
            $link = $minPlatform[Browser::PLATFORM_LINUX][Browser::BROWSER_CHROME];
        }
    }
}

if (!$link) {
    if ($browser->isMobile()) {
        $link = 'http://outdatedbrowser.com/ru';
    } else {
        $link = 'http://outdatedbrowser.com/ru';
    }
}

$this->title = __('You are using an outdated browser')
?>
<div id="container">
    <div class="section-center">
    </div>
    <p class="browserupgrade">
        <span>
            <?= __('You are using an outdated browser. Please {link} upgrade
        your browser {clink} to improve your experience.', ['link' => '<a href="' . $link . '">', 'clink' => '</a>']) ?>
            <br>
            <br>
            <?= __('Eski brauzerlar uchun ushbu saytning minimal talqini ishlab chiqilmoqda. Saytning minimal talqini tayyor bo\'lgunga qadar eski brauzerlarda ushbu sayt ochilmaydi. Buning uchun foydalanuvchilardan uzur so\'raymiz.') ?>
            <br>
            <br><b><?= __('YODDA TUTING!') ?></b><br>
            <?= __('Brauzerlarning eski versiyasi nafaqat saytlarni xato ishlashiga, balki turli viruslar, troyan dasturlar, spamlarni foydalanuvchi qurilmasiga o\'tishi va tizimning zararlanishiga olib keladi.') ?>
        </span>
        <br>
        <span class="continue"><a href="<?= $link ?>"><?= __('Yangilab olish') ?></a></span>

    <p>
</div>

