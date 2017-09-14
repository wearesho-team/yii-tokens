<?php


namespace Wearesho\Yii\Tests\Services;

use PHPUnit\Framework\TestCase;

use Wearesho\Yii\Services\TokenGenerator;

class TokenGeneratorTest extends TestCase
{
    public function testLength()
    {
        $generator = new TokenGenerator($tokenLength = 6);
        $token = $generator->getToken();
        $this->assertEquals(
            $tokenLength,
            mb_strlen($token),
            "getToken should return token with length passed to constructor"
        );
    }
}