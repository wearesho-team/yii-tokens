<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Mocks;

use Wearesho\Yii\Interfaces\TokenableEntityInterface;

class TokenableEntityMock implements TokenableEntityInterface
{
    protected string $tokenRecipient;

    protected array $tokenData;

    /**
     * @return array
     */
    public function getTokenData(): array
    {
        return $this->tokenData;
    }

    /**
     * @param array $tokenData
     */
    public function setTokenData(array $tokenData)
    {
        $this->tokenData = $tokenData;
    }

    /**
     * @return string
     */
    public function getTokenRecipient(): string
    {
        return $this->tokenRecipient;
    }

    /**
     * @param string $tokenRecipient
     */
    public function setTokenRecipient(string $tokenRecipient)
    {
        $this->tokenRecipient = $tokenRecipient;
    }
}
