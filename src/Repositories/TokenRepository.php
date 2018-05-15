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

use Wearesho\Delivery;

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

    /** @var Delivery\ServiceInterface */
    protected $deliveryService;

    /**
     * TokensRepository constructor.
     * @param TokenRecordInterface|null $model
     * @param TokenRepositoryConfigInterface $config
     * @param TokenGeneratorInterface $generator
     * @param Delivery\ServiceInterface $service
     */
    public function __construct(
        TokenRecordInterface $model,
        TokenRepositoryConfigInterface $config,
        TokenGeneratorInterface $generator,
        Delivery\ServiceInterface $service
    )
    {
        $this->model = $model;
        $this->generator = $generator;
        $this->config = $config;
        $this->deliveryService = $service;
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
     * @throws DeliveryLimitReachedException
     * @return bool
     * @throws ValidationException
     */
    public function send(TokenableEntityInterface $entity): bool
    {
        $token = $this->push($entity);
        if ($token->getDeliveryCount() >= $this->config->getDeliveryLimit()) {
            throw new DeliveryLimitReachedException(
                $token->getDeliveryCount(),
                $this->config->getExpirePeriod()
            );
        }

        try {
            $this->deliveryService->send($token);
        } catch (Delivery\Exception $e) {
            return false;
        }

        if ($token instanceof TokenRecordInterface) {
            $token->increaseDeliveryCount();
            ValidationException::saveOrThrow($token);
        }

        return true;
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
     * @throws ValidationException
     */
    public function verify(string $tokenRecipient, string $token): TokenInterface
    {
        $record = $this->pull($tokenRecipient);

        if (!$record instanceof TokenRecordInterface) {
            throw new InvalidRecipientException($tokenRecipient);
        }

        $record->increaseVerifyCount();

        ValidationException::saveOrThrow($record);

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
}
