<?php

declare(strict_types=1);

namespace Wearesho\Yii\Validators;

use Wearesho\Yii\Exceptions\DeliveryLimitReachedException;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;

use yii\base\Model;
use yii\validators\Validator;

class TokenValidator extends Validator
{
    /** @var  string */
    public $recipientAttribute;

    /** @var  callable */
    public $recipient;

    /** @var  string */
    public $targetAttribute;

    /** @var string */
    public $message;

    /** @var  string */
    public $limitReachedMessage;

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
