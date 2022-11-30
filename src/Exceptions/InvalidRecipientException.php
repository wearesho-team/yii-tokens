<?php

declare(strict_types=1);

namespace Wearesho\Yii\Exceptions;

class InvalidRecipientException extends TokenException
{
    protected string $recipient;

    public function __construct(string $tokenRecipient, $code = 0, \Throwable $previous = null)
    {
        $message = "Token for {$tokenRecipient} did not created yet.";
        parent::__construct($message, $code, $previous);

        $this->recipient = $tokenRecipient;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }
}
