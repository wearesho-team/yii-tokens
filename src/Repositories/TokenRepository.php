<?php


namespace Wearesho\Yii\Repositories;


use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Exceptions\InvalidRecipientException;
use Wearesho\Yii\Exceptions\InvalidTokenException;

use Wearesho\Yii\Exceptions\ValidationException;
use Wearesho\Yii\Interfaces\TokenableEntityInterface;
use Wearesho\Yii\Interfaces\TokenGeneratorInterface;
use Wearesho\Yii\Interfaces\TokenInterface;
use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;

use Wearesho\Yii\Interfaces\TokenSendServiceInterface;

/**
 * Class TokensRepository
 * @package Wearesho\Yii\Repositories
 */
class TokenRepository implements TokenRepositoryInterface
{
    /** @var  TokenRecordInterface */
    protected $model;

    /** @var  TokenRepositoryConfigInterface */
    protected $config;

    /** @var TokenGeneratorInterface */
    protected $generator;

    /**
     * TokensRepository constructor.
     * @param TokenRepositoryConfigInterface $config
     * @param TokenGeneratorInterface $generator
     * @param TokenRecordInterface|null $model
     */
    public function __construct(
        TokenRecordInterface $model,
        TokenRepositoryConfigInterface $config,
        TokenGeneratorInterface $generator
    )
    {
        $this->model = $model;
        $this->generator = $generator;
        $this->config = $config;
    }

    /**
     * Creating token (for example, when we receive first-stage data)
     * Will increase sending counter
     *
     * @param TokenableEntityInterface $entity
     * @return TokenInterface|TokenRecordInterface
     * @throws ValidationException
     */
    public function push(TokenableEntityInterface $entity): TokenInterface
    {
        $record = $this->pull($entity->getTokenRecipient());
        if (!$record instanceof TokenRecordInterface) {
            $class = get_class($this->model);
            /** @var TokenRecordInterface $record */
            $record = new $class;

            $record->setRecipient($entity->getTokenRecipient());
            $record->setToken($this->generator->getToken());
        }

        $record->setData($entity->getTokenData());
        ValidationException::saveOrThrow($record);

        return $record;
    }

    /**
     * Creating and sending token
     * Internal should use push() method
     *
     * @todo: preventing two write operations (update+update, insert+update)
     *
     * @param TokenableEntityInterface $entity
     * @param TokenSendServiceInterface $sender
     * @throws DeliveryLimitReachedException
     * @return bool
     * @throws ValidationException
     */
    public function send(TokenableEntityInterface $entity, TokenSendServiceInterface $sender): bool
    {
        $token = $this->push($entity);
        if ($token->getDeliveryCount() >= $this->config->getDeliveryLimit()) {
            throw new DeliveryLimitReachedException(
                $token->getDeliveryCount(),
                $this->config->getExpirePeriod()
            );
        }
        $delivered = $sender->send($token);

        if ($token instanceof TokenRecordInterface && $delivered) {
            $token->increaseDeliveryCount();
            ValidationException::saveOrThrow($token);
        }

        return $delivered;
    }

    /**
     * Pulling active token to process it (for example, sending sms)
     *
     * @param string $tokenRecipient
     * @return null|TokenRecordInterface
     */
    public function pull(string $tokenRecipient)
    {
        return $this->model->find()
            ->notExpired($this->config->getExpirePeriod())
            ->whereRecipient($tokenRecipient)
            ->one();
    }

    /**
     * Will return token with data to process registration if token valid
     * Or throw one of exceptions
     * Should delete token from storage if token valid to prevent double validation for single token
     *
     * @param string $tokenRecipient
     * @param string $token
     * @return TokenInterface
     * @throws DeliveryLimitReachedException
     * @throws InvalidRecipientException
     * @throws InvalidTokenException
     */
    public function verify(string $tokenRecipient, string $token): TokenInterface
    {
        $record = $this->pull($tokenRecipient);

        if (!$record instanceof TokenRecordInterface) {
            throw new InvalidRecipientException($tokenRecipient);
        }

        $record->increaseVerifyCount();
        if ($this->config->getVerifyLimit() < $record->getVerifyCount()) {
            throw new DeliveryLimitReachedException(
                $record->getVerifyCount(),
                $this->config->getExpirePeriod()
            );
        }

        ValidationException::saveOrThrow($record);

            throw new InvalidTokenException($token);
        }

        $record->delete();

        return $record;
    }

}