<?php

namespace Wearesho\Yii\Tests\Services;

use PHPUnit\Framework\TestCase;

use Wearesho\Yii\Services\TokenGenerator;

/**
 * Class TokenGeneratorTest
 * @package Wearesho\Yii\Tests\Services
 *
 * @internal
 */
class TokenGeneratorTest extends TestCase
{
    /** @var TokenGenerator */
    protected $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new TokenGenerator($tokenLength = 6);
    }

    public function testLength(): void
    {
        $token = $this->generator->getToken();
        $this->assertEquals(
            6,
            mb_strlen($token),
            "getToken should return token with length passed to constructor"
        );
    }

    public function testDefaultEnvironmentToken(): void
    {
        $defaultEnvironmentToken = 123456;

        putenv('TOKEN_GENERATOR_DEFAULT');
        $this->assertNotEquals($defaultEnvironmentToken, $this->generator->getToken());

        putenv('TOKEN_GENERATOR_DEFAULT=' . $defaultEnvironmentToken);
        $this->assertEquals($defaultEnvironmentToken, $this->generator->getToken());
    }
}
