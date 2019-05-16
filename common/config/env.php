<?php
/**
 * Setup application environment
 */
$dotenv = new \Dotenv\Dotenv(dirname(dirname(__DIR__)));
$dotenv->load();


defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') == 'true');
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');
defined('ASSET_BUNDLE') or define('ASSET_BUNDLE', getenv('ASSET_BUNDLE') == 'true');