<?php


namespace Wearesho\Yii\Tests\Mocks;

use Wearesho\Yii\Interfaces\TokenableEntityInterface;

/**
 * Class TokenableEntityMock
 * @package Wearesho\Yii\Tests\Mocks
 *
 * @internal
 */
class TokenableEntityMock implements TokenableEntityInterface
{

    /** @var  string */
    protected $tokenRecipient;

    /** @var  array */
    protected $tokenData;

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
