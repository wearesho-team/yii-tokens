<?php


namespace Wearesho\Yii\Exceptions;


/**
 * Class InvalidTokenException
 * @package Wearesho\Yii\Exceptions
 */
class InvalidTokenException extends RegistrationException
{
    /** @var string  */
    protected $token;

    /**
     * InvalidTokenException constructor.
     * @param string $token
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $token, $code = 0, \Throwable $previous = null)
    {
        $message = "Token {$token} is invalid.";
        parent::__construct($message, $code, $previous);

        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}