<?php

use Dotenv\Dotenv;

require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

$env = new Dotenv(dirname(__DIR__));

if(file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env')) {
    $env->load();
}

$env->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_TYPE',]);
