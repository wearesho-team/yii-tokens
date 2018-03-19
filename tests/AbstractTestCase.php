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
 *
 * @internal
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var Migration[]
     */
    private $migrations = [];

    protected function setUp()
    {
        parent::setUp();

        $dsn = getenv('DB_TYPE') . ":host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_NAME");

        $config = [
            'id' => 'yii-register-confirmation-test',
            'basePath' => dirname(__DIR__),
            'components' => [
                'db' => [
                    'class' => Connection::class,
                    'dsn' => $dsn,
                    'username' => getenv('DB_USER'),
                    'password' => getenv('DB_PASS') ?: '',
                ],
            ],
        ];

        \Yii::$app = new Application($config);
        $this->migrations = [];
        foreach (new DirectoryIterator(dirname(__DIR__) . "/migrations") as $file) {
            if (!$file->isFile()) {
                continue;
            }
            include_once $file->getRealPath();
            $class = str_replace('.php', '', $file);
            $this->migrations[] = $migration = new $class;
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

        /** @var Migration $migration */
        foreach (array_reverse($this->migrations) as $migration) {
            ob_start();
            $migration->down();
            ob_end_clean();
        }
        \Yii::$app = null;
    }
}
