<?php

namespace Wearesho\Yii\Entities;

use Wearesho\Yii\Interfaces\TokenableEntityInterface;

/**
 * @since 1.2.2
 */
class TokenableEntity implements TokenableEntityInterface
{
    protected string $recipient;

    protected array $data;

    public function __construct(string $recipient, array $data = [])
    {
        $this->recipient = $recipient;
        $this->data = $data;
    }

    public function getTokenRecipient(): string
    {
        return $this->recipient;
    }

    public function getTokenData(): array
    {
        return $this->data;
    }
}
