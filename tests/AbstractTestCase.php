<?php

namespace Wearesho\Yii\Tests;


use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
        init_application();
    }

    protected function tearDown()
    {
        parent::tearDown();
        destroy_application();
    }
}