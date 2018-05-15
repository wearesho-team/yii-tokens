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
     * @param TokenableEntityInterface $entity
     * @return TokenInterface
     */
    public function push(TokenableEntityInterface $entity): TokenInterface;

    /**
     * Creating and sending token
     * Will increase sending counter
     *
     * @param TokenableEntityInterface $entity
     *
     * @throws DeliveryLimitReachedException
     */
    public function send(TokenableEntityInterface $entity);

    /**
     * Pulling active token to process it (for example, sending sms)
     * Will not increase sending counter
     *
     * @param string $tokenRecipient
     * @return TokenInterface|null
     */
    public function pull(string $tokenRecipient);

    /**
     * Will return token with data to process action if token valid
     * Or throw one of exceptions
     * Should delete token from storage if token valid to prevent double validation for single token
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
