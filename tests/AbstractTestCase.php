<?php

namespace Wearesho\Yii\Tests;


use PHPUnit\Framework\TestCase;

use yii\console\Application;

use yii\db\Migration;
use yii\db\Connection;

use \DirectoryIterator;

/**
 * Class AbstractTestCase
 * @package Wearesho\Yii\Tests
 */
abstract class AbstractTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        if (file_exists($_ENV['DB_PATH'])) {
            unlink($_ENV['DB_PATH']);
        }
        file_put_contents($_ENV['DB_PATH'], '');
        chmod($_ENV['DB_PATH'], 0755);

        $config = [
            'id' => 'yii-register-confirmation-test',
            'basePath' => dirname(__DIR__),
            'components' => [
                'db' => [
                    'class' => Connection::class,
                    'dsn' => 'sqlite:' . $_ENV['DB_PATH'],
                ],
            ],
        ];

        \Yii::$app = new Application($config);
        foreach (new DirectoryIterator(dirname(__DIR__) . "/migrations") as $file) {
            if (!$file->isFile()) {
                continue;
            }
            include_once $file->getRealPath();
            $class = str_replace('.php', '', $file);
            $migration = new $class;
            if (!$migration instanceof Migration) {
                continue;
            }

            ob_start();
            $migration->up();
            ob_end_clean();
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Yii::$app = null;
        if (file_exists($_ENV['DB_PATH'])) {
            unlink($_ENV['DB_PATH']);
        }
    }
}