<?php


namespace Wearesho\Yii\Tests\Mocks;


use Wearesho\Yii\Interfaces\TokenInterface;
use Wearesho\Yii\Interfaces\TokenSendServiceInterface;

/**
 * Class TokenSendServiceMock
 * @package Wearesho\Yii\Tests\Mocks
 */
class TokenSendServiceMock implements TokenSendServiceInterface
{
    /** @var  bool */
    protected $expectedResult = true;

    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function send(TokenInterface $token): bool
    {
        return $this->expectedResult;
    }

    /**
     * @param bool $expectedResult
     */
    public function setExpectedResult(bool $expectedResult)
    {
        $this->expectedResult = $expectedResult;
    }

}