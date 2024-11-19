<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

use Wearesho\Delivery;

interface TokenableEntityInterface extends Delivery\MessageInterface
{
    public function getTokenType(): string;

    public function getTokenData(): array;
}
