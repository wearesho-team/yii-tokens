<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Mocks;

use Wearesho\Yii\Models\Token;

class TokenRecordMock extends Token
{
    public static function getType(): string
    {
        return "mock";
    }
}
