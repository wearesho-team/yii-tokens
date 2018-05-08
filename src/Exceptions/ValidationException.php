<?php


namespace Wearesho\Yii\Exceptions;

use yii\base\Model;
use yii\db\ActiveRecordInterface;

/**
 * Class ValidationException
 * @package Wearesho\Yii\Exceptions
 */
class ValidationException extends TokenException
{
    /** @var  Model */
    protected $model;

    /**
     * ValidationException constructor.
     * @param Model $model
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(Model $model, $code = 0, \Throwable $previous = null)
    {
        $message = "Error validating " . get_class($model);
        parent::__construct($message, $code, $previous);

        $this->model = $model;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param ActiveRecordInterface $record
     * @return ActiveRecordInterface
     */
    public static function saveOrThrow(ActiveRecordInterface $record): ActiveRecordInterface
    {
        if (!$record->save() && $record instanceof Model) {
            throw new static($record);
        }
        return $record;
    }
}
