<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

namespace console\controllers;

use yii\console\Controller;
use yii\console\widgets\Table;
use yii\helpers\Console;

/**
 * This command change you app environments that you have entered.
 * @since  2.0
 */
class EnvController extends Controller
{
    protected static $_envs = [
        'dev',
        'prod',
        'test',
    ];

    /**
     * This command changes what you have selected as the application environment.
     * @param string $env the application environment to be changed.
     * @return bool|int
     */
    public function actionIndex($env = 'prod')
    {
        if (in_array($env, self::$_envs)) {

            $file    = \Yii::getAlias('@root/.env');
            $content = preg_replace('/(YII_ENV\s*=\s*)(""|\'\'|prod|dev|test)/', "\\1$env", file_get_contents($file));
            if (file_put_contents($file, $content)) {
                $this->stdout("Your application was under '$env'\n", Console::FG_GREEN);
                return true;
            }
        }
        $this->stderr("Unknown environment. Please enter valid\n", Console::FG_RED);
        return false;
    }

    /**
     * This command changes what you have liked as the environment variable.
     */
    public function actionSet()
    {

        $file = \Yii::getAlias('@root/.env');
        preg_match_all('/([A-Z_]+)/', file_get_contents($file), $matches);
        $vars = [];
        foreach ($matches[0] as $k => $match) {
            if (strlen($match) > 1 && $var = getenv($match)) {
                $vars[$match] = $var;
            }
        }
        $opts = array_keys($vars);
        foreach ($opts as $key => $value) {
            $this->stdout("$key -> $value\n", Console::FG_GREEN);
        }
        if ($selected = $this->prompt("Select any variable:", ['required' => true])) {
            $content = file_get_contents($file);
            if ($new = $this->prompt("Enter new value for '" . $opts[$selected] . "':")) {
                $content = str_replace($vars[$opts[$selected]], $new, $content);
                file_put_contents($file, $content);
                $this->stdout("Variable '" . $opts[$selected] . "' changed to $new\n", Console::FG_BLUE);
            }
        }

    }

    /**
     * This command echoes application environments
     */
    public function actionCat()
    {
        $file = \Yii::getAlias('@root/.env');
        preg_match_all('/([A-Z_]+)/', file_get_contents($file), $matches);
        $vars = [];
        foreach ($matches[0] as $k => $match) {
            if (strlen($match) > 1 && $var = getenv($match)) {
                $vars[] = [$match, $var];
            }
        }
        echo Table::widget([
                               'headers' => ['ID', 'Name'],
                               'rows'    => $vars,
                           ]);
        /*foreach ($vars as $key => $value) {
            $this->stdout("$key\t\t = $value\n");
        }*/
    }

}
