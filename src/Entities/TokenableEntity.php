<?php

namespace Wearesho\Yii\Entities;

use Wearesho\Yii\Interfaces\TokenableEntityInterface;

/**
 * @since 1.2.2
 */
class TokenableEntity implements TokenableEntityInterface
{
    public function __construct(
        protected string $recipient,
        protected string $text,
        protected string $tokenType,
        protected array $data
    )
    {
    }

    public function getTokenData(): array
    {
        return $this->data;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}
