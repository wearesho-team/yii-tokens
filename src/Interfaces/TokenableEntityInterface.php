<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

interface TokenableEntityInterface
{
    public function getTokenRecipient(): string;

    public function getTokenData(): array;
}
