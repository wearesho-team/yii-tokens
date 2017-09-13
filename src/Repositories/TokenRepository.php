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
use Wearesho\Yii\Interfaces\TokenRepositorySettingsInterface;

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

    /** @var  TokenRepositorySettingsInterface */
    protected $settings;

    /** @var TokenGeneratorInterface */
    protected $generator;

    /**
     * TokensRepository constructor.
     * @param TokenRepositorySettingsInterface $settings
     * @param TokenGeneratorInterface $generator
     * @param TokenRecordInterface|null $model
     */
    public function __construct(
        TokenRepositorySettingsInterface $settings,
        TokenGeneratorInterface $generator = null,
        TokenRecordInterface $model = null
    )
    {
        $this->model = $model ?? new RegistrationToken;
        $this->generator = $generator ?? new TokenGenerator;
        $this->settings = $settings;
    }

    /**
     * Creating token (for example, when we receive first-stage data)
     *
     * @param RegistrationEntityInterface $entity
     * @return TokenInterface
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
     * Pulling active token to process it (for example, sending sms)
     *
     * @param string $tokenRecipient
     * @throws DeliveryLimitReachedException
     * @return null|TokenRecordInterface
     */
    public function pull(string $tokenRecipient)
    {
        $record = $this->model->find()
            ->notExpired($this->settings->getExpirePeriod())
            ->whereRecipient($tokenRecipient)
            ->one();

        if ($record instanceof TokenRecordInterface) {
            $record->increaseDeliveryCount();
            if ($record->getDeliveryCount() > $this->settings->getDeliveryLimit()) {
                throw new DeliveryLimitReachedException(
                    $this->settings->getDeliveryLimit(),
                    $this->settings->getExpirePeriod()
                );
            }
            ValidationException::saveOrThrow($record);
        }

        return $record;
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
            ->notExpired($this->settings->getExpirePeriod())
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