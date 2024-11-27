<?php

declare(strict_types=1);

namespace Wearesho\Yii\Validators;

use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Interfaces\TokenableEntityInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\validators\Validator;

class TokenValidator extends Validator
{
    public ?string $recipientAttribute = null;

    public TokenableEntityInterface|\Closure|null $recipient = null;

    public ?string $targetAttribute = null;

    public $message = null;

    public ?string $limitReachedMessage = null;

    protected TokenRepositoryInterface $repository;

    public function __construct(TokenRepositoryInterface $repository, array $config = [])
    {
        parent::__construct($config);

        $this->repository = $repository;
        $this->message = $this->message ?: \Yii::t('yii', '{attribute} is invalid.');
        $this->limitReachedMessage = $this->limitReachedMessage ?: \Yii::t('yii', 'Limit of messages is reached');
    }

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $recipient = is_callable($this->recipient)
            ? call_user_func($this->recipient, $model, $attribute)
            : $model->{$this->recipientAttribute};

        if (!$recipient instanceof TokenableEntityInterface) {
            throw new InvalidConfigException(
                "recipient attribute have to be either TokenableEntityInterface "
                . "or Closure with return type of TokenableEntityInterface"
            );
        }

        $token = $model->{$attribute};

        try {
            $token = $this->repository->verify($recipient, $token);
            if (!empty($this->targetAttribute) && $model->canSetProperty($this->targetAttribute)) {
                $model->{$this->targetAttribute} = $token;
            }
        } catch (DeliveryLimitReachedException $ex) {
            $this->addError($model, $attribute, $this->limitReachedMessage);
        } catch (\Throwable $ex) {
            $this->addError($model, $attribute, $this->message, [
                'attribute' => $attribute,
            ]);
        }
    }
}
