<?php

use yii\helpers\ArrayHelper;

define('DS', DIRECTORY_SEPARATOR);
define('HTTPS_ON', isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('root', dirname(dirname(__DIR__)) . DS);
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('static', dirname(dirname(__DIR__)) . '/static');
Yii::setAlias('api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('backups', dirname(dirname(__DIR__)) . '/backups');
Yii::setAlias('modules', dirname(dirname(__DIR__)) . '/modules');

Yii::setAlias('@frontendUrl', getenv('FRONTEND_URL'));
Yii::setAlias('@backendUrl', getenv('BACKEND_URL'));
Yii::setAlias('@staticUrl', getenv('STATIC_URL'));
Yii::setAlias('@apiUrl', getenv(HTTPS_ON ? 'API_URL' : 'API_URL'));

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
    $_SERVER['HTTPS'] = 'on';

if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ips                    = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $_SERVER['REMOTE_ADDR'] = trim($ips[0]);
} elseif (isset($_SERVER['HTTP_X_REAL_IP']) && !empty($_SERVER['HTTP_X_REAL_IP'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP'];
} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
}


function __($message, $params = array(), $category = 'app.ui')
{
    $language = Yii::$app->language;
    switch (Yii::$app->id) {
        case 'app-backend' :
            $category = 'app.backend';
            break;
        case 'app-frontend' :
            $category = 'app.frontend';
            break;
        case 'app-console' :
            $category = 'app.console';
            break;
    }

    $params = ArrayHelper::merge($params, [
        'br'  => '<br>',
        'b'   => '<b>',
        'bc'  => '</b>',
        'sp'  => '<span>',
        'spc' => '</span>',
        'em'  => '<em>',
        '/em' => '</em>',
    ]);

    /* @var $mongodb \yii\mongodb\Connection */
    /*$mongodb    = Yii::$app->mongodb;
    $collection = $mongodb->getCollection('_system_message');
    $collection->remove(['message' => '']);

    $old = ['category' => 'app.ui', 'message' => $message];
    $msg = $collection->findOne($old);
    if ($msg != null) {
        try {
            $collection->update($old, [
                'category' => $category,
                'message'  => $message
            ]);

        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
    } else {*/
        return Yii::t($category, trim($message), $params, $language);
    //}
}