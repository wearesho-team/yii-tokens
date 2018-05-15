<?php

namespace Wearesho\Yii\Tests\Mocks;

use Wearesho\Delivery;

/**
 * Class TokenSendServiceMock
 * @package Wearesho\Yii\Tests\Mocks
 *
 * @internal
 */
class TokenSendServiceMock implements Delivery\ServiceInterface
{
    /** @var  bool */
    protected $expectedResult = true;

    public function send(Delivery\MessageInterface $message): void
    {
    }

    /**
     * @param bool $expectedResult
     */
    public function setExpectedResult(bool $expectedResult)
    {
        $this->expectedResult = $expectedResult;
    }
}
