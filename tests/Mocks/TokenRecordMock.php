<?php


namespace Wearesho\Yii\Tests\Mocks;


use Wearesho\Yii\Models\Token;

/**
 * Class TokenRecordMock
 * @package Wearesho\Yii\Tests\Mocks
 *
 * @internal
 */
class TokenRecordMock extends Token
{
    /**
     * @return string
     */
    public static function getType(): string
    {
        return "mock";
    }
}
