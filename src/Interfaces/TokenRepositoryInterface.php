<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Exceptions\InvalidRecipientException;
use Wearesho\Yii\Exceptions\InvalidTokenException;

interface TokenRepositoryInterface
{
    /**
     * Creating token (for example, when we receive first-stage data)
     */
    public function push(TokenableEntityInterface $entity): TokenInterface;

    /**
     * Creating and sending token
     * Will increase sending counter
     *
     * @throws DeliveryLimitReachedException
     */
    public function send(TokenableEntityInterface $entity);

    /**
     * Pulling active token to process it (for example, sending sms)
     * Will not increase sending counter
     *
     * @param TokenableEntityInterface $entity
     * @return TokenInterface|null
     */
    public function pull(TokenableEntityInterface $entity);

    /**
     * Will return token with data to process action if token valid
     * Or throw one of exceptions
     * Should delete token from storage if token valid to prevent double validation for single token
     *
     * @param TokenableEntityInterface $entity
     * @param string $token
     *
     * @throws InvalidTokenException
     * @throws InvalidRecipientException
     *
     * @return TokenInterface
     */
    public function verify(TokenableEntityInterface $entity, string $token): TokenInterface;
}
