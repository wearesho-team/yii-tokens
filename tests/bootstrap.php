<?php

// phpcs:ignoreFile

require_once dirname(__DIR__) . "/vendor/autoload.php";
require_once dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

$repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
    ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
    ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)
    ->immutable()
    ->make();
$dotenv = Dotenv\Dotenv::create($repository, dirname(__DIR__));
$dotenv->load();
$dotenv->safeLoad();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_TYPE', 'DB_PORT',]);
