<?php


namespace Wearesho\Yii\Exceptions;


use Throwable;

/**
 * Class InvalidRecipientException
 * @package Wearesho\Yii\Exceptions
 */
class InvalidRecipientException extends RegistrationException
{
    /** @var string */
    protected $recipient;

    /**
     * InvalidRecipientException constructor.
     * @param string $tokenRecipient
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $tokenRecipient, $code = 0, Throwable $previous = null)
    {
        $message = "Token for {$tokenRecipient} did not created yet.";
        parent::__construct($message, $code, $previous);

        $this->recipient = $tokenRecipient;
    }

    /**
     * @return string
     */
    public function getRecipient(): string
    {
        return $this->recipient;
    }
}