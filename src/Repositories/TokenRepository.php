<?php

declare(strict_types=1);

namespace Wearesho\Yii\Repositories;

use Wearesho\Yii\Entities\TokenableEntity;
use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Exceptions\InvalidRecipientException;
use Wearesho\Yii\Exceptions\InvalidTokenException;

use Wearesho\Yii\Interfaces\TokenableEntityInterface;
use Wearesho\Yii\Interfaces\TokenGeneratorInterface;
use Wearesho\Yii\Interfaces\TokenInterface;
use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;

use Horat1us\Yii\Validation;
use Wearesho\Delivery;
use Wearesho\Yii\Models\Token;

class TokenRepository implements TokenRepositoryInterface
{
    public function __construct(
        protected TokenRepositoryConfigInterface $config,
        protected TokenGeneratorInterface        $generator,
        protected Delivery\ServiceInterface      $deliveryService
    ) {
    }

    /**
     * Creating token (for example, when we receive first-stage data)
     * Will increase sending counter
     *
     * @param TokenableEntityInterface $entity
     * @return TokenInterface|TokenRecordInterface
     * @throws Validation\Failure
     */
    public function push(TokenableEntityInterface $entity): TokenInterface
    {
        $record = $this->pull($entity);
        if (!$record instanceof TokenRecordInterface) {
            $record = new Token();
            $record->type = $entity->getTokenType();
            $record->setRecipient($entity->getRecipient());
            $record->setToken($this->generator->getToken());
        }

        $record->setData($entity->getTokenData());
        Validation\Exception::saveOrThrow($record);

        return $record;
    }

    /**
     * Creating and sending token
     * Internal should use push() method
     *
     * @param TokenableEntityInterface $entity
     * @throws DeliveryLimitReachedException
     * @throws Delivery\Exception
     * @throws Validation\Failure
     * @todo: preventing two write operations (update+update, insert+update)
     *
     */
    public function send(TokenableEntityInterface $entity): void
    {
        $token = $this->push($entity);
        if ($token->getDeliveryCount() >= $this->config->getDeliveryLimit()) {
            throw new DeliveryLimitReachedException(
                $token->getDeliveryCount(),
                $this->config->getExpirePeriod()
            );
        }

        $entityWithToken = new TokenableEntity(
            $entity->getRecipient(),
            str_replace('{token}', $token->getToken(), $entity->getText()),
            $entity->getTokenType(),
            $entity->getTokenData()
        );
        $this->deliveryService->send($entityWithToken);

        if ($token instanceof TokenRecordInterface) {
            $token->increaseDeliveryCount();
            Validation\Exception::saveOrThrow($token);
        }
    }

    /**
     * Pulling active token to process it (for example, sending sms)
     */
    public function pull(TokenableEntityInterface $entity): ?TokenRecordInterface
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Token::find()
            ->andWhere(['=', 'token.type', $entity->getTokenType()])
            ->notExpired($this->config->getExpirePeriod())
            ->whereRecipient($entity->getRecipient())
            ->one();
    }

    /**
     * Will return token with data to process registration if token valid
     * Or throw one of exceptions
     * Should delete token from storage if token valid to prevent double validation for single token
     *
     * @return TokenInterface
     * @throws DeliveryLimitReachedException
     * @throws InvalidRecipientException
     * @throws InvalidTokenException
     * @throws Validation\Failure
     */
    public function verify(TokenableEntityInterface $entity, string $token): TokenInterface
    {
        $record = $this->pull($entity);

        if (!$record instanceof TokenRecordInterface) {
            throw new InvalidRecipientException($entity->getRecipient());
        }

        $record->increaseVerifyCount();

        Validation\Exception::saveOrThrow($record);

        if ($this->config->getVerifyLimit() < $record->getVerifyCount()) {
            throw new DeliveryLimitReachedException(
                $record->getVerifyCount(),
                $this->config->getExpirePeriod()
            );
        }

        if ($record->getToken() !== $token) {
            throw new InvalidTokenException($token);
        }

        $record->delete();

        return $record;
    }

    public function delete(TokenableEntityInterface $entity): void
    {
        $record = $this->pull($entity);

        if (!$record instanceof TokenRecordInterface) {
            throw new InvalidRecipientException($entity->getRecipient());
        }

        if ($record instanceof Token) {
            $record->delete();
        }
    }
}
