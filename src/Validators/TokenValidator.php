<?php

namespace Wearesho\Yii\Validators;

use Wearesho\Yii\Interfaces\TokenInterface;
use Wearesho\Yii\Interfaces\TokenRepositoryInterface;

use yii\base\Model;
use yii\validators\Validator;

/**
 * Class TokenValidator
 * @package Wearesho\Yii\Validators
 */
class TokenValidator extends Validator
{
    /** @var  string */
    public $recipientAttribute;

    /** @var string */
    public $message = "Token is invalid";

    /** @var TokenRepositoryInterface */
    protected $repository;

    /**
     * TokenValidator constructor.
     * @param array $config
     * @param TokenRepositoryInterface $repository
     */
    public function __construct(array $config = [], TokenRepositoryInterface $repository)
    {
        parent::__construct($config);
        $this->repository = $repository;
    }

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $recipient = $model->{$recipient};
        $token = $model->{$attribute};

        $storedToken = $this->repository->pull($recipient);

        if (
            !$storedToken instanceof TokenInterface
            || $storedToken->getToken() !== $token
        ) {
            $this->addError($model, $attribute, $this->message);
        }
    }
}