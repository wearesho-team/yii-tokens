<?php


namespace Wearesho\Yii\Repositories;


use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Exceptions\InvalidRecipientException;
use Wearesho\Yii\Exceptions\InvalidTokenException;

use Wearesho\Yii\Exceptions\ValidationException;
use Wearesho\Yii\Interfaces\RegistrationEntityInterface;
use Wearesho\Yii\Interfaces\TokenGeneratorInterface;
use Wearesho\Yii\Interfaces\TokenInterface;
use Wearesho\Yii\Interfaces\TokenRecordInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;

use Wearesho\Yii\Interfaces\TokenSendServiceInterface;
use Wearesho\Yii\Models\RegistrationToken;
use Wearesho\Yii\Services\TokenGenerator;

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
        TokenRepositoryConfigInterface $config,
        TokenGeneratorInterface $generator = null,
        TokenRecordInterface $model = null
    )
    {
        $this->model = $model ?? new RegistrationToken;
        $this->generator = $generator ?? new TokenGenerator;
        $this->config = $config;
    }

    /**
     * Creating token (for example, when we receive first-stage data)
     *
     * @param RegistrationEntityInterface $entity
     * @return TokenInterface|TokenRecordInterface
     */
    public function push(RegistrationEntityInterface $entity): TokenInterface
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
     * @param RegistrationEntityInterface $entity
     * @param TokenSendServiceInterface $sender
     * @throws DeliveryLimitReachedException
     * @return bool
     */
    public function send(RegistrationEntityInterface $entity, TokenSendServiceInterface $sender): bool
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
     * @throws DeliveryLimitReachedException
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
     *
     * @param string $tokenRecipient
     * @param string $token
     *
     * @throws InvalidTokenException
     * @throws InvalidRecipientException
     *
     * @return TokenInterface
     */
    public function verify(string $tokenRecipient, string $token): TokenInterface
    {
        $record = $this->model->find()
            ->notExpired($this->config->getExpirePeriod())
            ->whereRecipient($tokenRecipient)
            ->one();

        if (!$record instanceof TokenRecordInterface) {
            throw new InvalidRecipientException($tokenRecipient);
        }

        if ($record->getToken() !== $token) {
            throw new InvalidTokenException($token);
        }

        $record->increaseVerifyCount();

        return $record;
    }

}