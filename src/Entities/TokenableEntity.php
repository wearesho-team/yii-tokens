<?php

namespace Wearesho\Yii\Entities;

use Wearesho\Yii\Interfaces\TokenableEntityInterface;

/**
 * Class TokenableEntity
 * @package Wearesho\Yii\Entities
 *
 * @since 1.2.2
 */
class TokenableEntity implements TokenableEntityInterface
{
    /** @var string */
    protected $recipient;

    /** @var array */
    protected $data;

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
