<?php
require_once __DIR__ . '/../../common/components/Browser.php';

use common\components\Browser;

$path = $_SERVER['REQUEST_URI'];

$browser     = new Browser();
$minVersions = [
    Browser::BROWSER_IE         => 10,
    Browser::BROWSER_EDGE       => 13,
    Browser::BROWSER_CHROME     => 22,
    Browser::BROWSER_FIREFOX    => 22,
    Browser::BROWSER_SAFARI     => 8,
    Browser::BROWSER_OPERA      => 12.1,
    Browser::BROWSER_OPERA_MINI => 0,
    Browser::BROWSER_ANDROID    => 4.1,
    Browser::BROWSER_BLACKBERRY => 4,
    Browser::BROWSER_YANDEX     => 14,
    Browser::BROWSER_UCBROWSER  => 11.4,
];
$minPlatform = [
    Browser::PLATFORM_ANDROID => [
        Browser::BROWSER_CHROME  => 62,
        Browser::BROWSER_SAMSUNG => 4,
        Browser::BROWSER_FIREFOX => 57,
    ],
];

function logData($browser)
{
    file_put_contents(__DIR__ . DS . '../runtime/browser.update.log', $_SERVER['REMOTE_ADDR'] . ' ' . $browser->getPlatform() . ' ' . $browser->getBrowser() . ' ' . $browser->getVersion() . ' || ' . $_SERVER['HTTP_USER_AGENT'] . ' || ' . $_SERVER['REQUEST_URI'] . PHP_EOL, FILE_APPEND);
}

if (isset($minVersions[$browser->getBrowser()])) {
    if (version_compare($browser->getVersion(), $minVersions[$browser->getBrowser()]) === -1) {
        define("MIN_VERSION", 1);
    }
} else if (isset($minPlatform[$browser->getPlatform()])) {
    if (isset($minPlatform[$browser->getPlatform()][$browser->getBrowser()])) {
        if (version_compare($browser->getVersion(), $minPlatform[$browser->getPlatform()][$browser->getBrowser()]) === -1) {
            define("MIN_VERSION", 1);
        }
    }
}

function minVersion()
{
    return defined('MIN_VERSION') || 1;
}
