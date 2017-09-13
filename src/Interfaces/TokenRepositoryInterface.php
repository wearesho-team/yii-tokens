<?php

namespace Wearesho\Yii\Interfaces;

use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Exceptions\InvalidRecipientException;
use Wearesho\Yii\Exceptions\InvalidTokenException;


/**
 * Interface TokensRepositoryInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenRepositoryInterface
{
    /**
     * Creating token (for example, when we receive first-stage data)
     *
     * @param RegistrationEntityInterface $entity
     * @return TokenInterface
     */
    public function push(RegistrationEntityInterface $entity): TokenInterface;

    /**
     * Pulling active token to process it (for example, sending sms)
     * Will increase sending counter
     *
     * @param string $tokenRecipient
     * @throws DeliveryLimitReachedException
     * @return TokenInterface|null
     */
    public function pull(string $tokenRecipient);

    /**
     * Will return token with data to process registration if token valid
     * Or throw one of exceptions
     *
     * @param string $tokenRecipient
     * @param string $token
     *
     * @throws InvalidTokenException
     * @throws InvalidRecipientException
     *
     * @return TokenInterface
     */
    public function verify(string $tokenRecipient, string $token): TokenInterface;
}