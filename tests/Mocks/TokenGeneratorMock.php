<?php


namespace Wearesho\Yii\Tests\Mocks;


use Wearesho\Yii\Interfaces\TokenGeneratorInterface;

/**
 * Class TokenGeneratorMock
 * @package Wearesho\Yii\Tests\Mocks
 */
class TokenGeneratorMock implements TokenGeneratorInterface
{
    /** @var string  */
    protected $expectedToken = "111111";

    /**
     * @param string $expectedToken
     */
    public function setExceptedToken(string $expectedToken)
    {
        $this->expectedToken = $expectedToken;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->expectedToken;
    }
}