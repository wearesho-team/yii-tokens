<?php

namespace Wearesho\Yii\Validators;

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

    /** @var  string */
    public $targetAttribute;

    /** @var string */
    public $message;

    /** @var TokenRepositoryInterface */
    protected $repository;

    /**
     * TokenValidator constructor.
     * @param array $config
     * @param TokenRepositoryInterface $repository
     */
    public function __construct(TokenRepositoryInterface $repository, array $config = [])
    {
        parent::__construct($config);

        $this->repository = $repository;
        $this->message = \Yii::t('yii', '{attribute} is invalid.');
    }

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $recipient = $model->{$this->recipientAttribute};
        $token = $model->{$attribute};

        try {
            $token = $this->repository->verify($recipient, $token);
            if (!empty($this->targetAttribute) && $model->canSetProperty($this->targetAttribute)) {
                $model->{$this->targetAttribute} = $token;
            }
        } catch (\Throwable $ex) {
            $this->addError($model, $attribute, $this->message, [
                'attribute' => $attribute,
            ]);
        }
    }
}