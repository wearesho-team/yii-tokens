<?php

namespace Wearesho\Yii\Interfaces;

/**
 * Interface RegistrationEntityInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenableEntityInterface
{
    /**
     * @return string
     */
    public function getTokenRecipient(): string;

    /**
     * @return array
     */
    public function getTokenData(): array;
}
