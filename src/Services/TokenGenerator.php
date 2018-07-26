<?php

namespace Wearesho\Yii\Services;

use Wearesho\Yii\Interfaces\TokenGeneratorInterface;

/**
 * Class TokenGenerator
 * @package Wearesho\Yii\Services
 */
class TokenGenerator implements TokenGeneratorInterface
{
    /** @var int */
    protected $length;

    public function __construct(int $length = 6)
    {
        $this->length = $length;
    }

    public function getToken(): string
    {
        $environmentToken = $this->getDefaultEnvironmentToken();
        if (!empty($environmentToken)) {
            return $environmentToken;
        }

        $numbers = range(0, 9);

        return implode('', array_map(function () use (&$numbers) {
            return array_rand($numbers);
        }, range(1, $this->length)));
    }

    public function getIntegerToken(): int
    {
        $environmentToken = $this->getDefaultEnvironmentToken();
        if (!empty($environmentToken)) {
            if (!is_numeric($environmentToken)) {
                throw new \BadMethodCallException("Environment token {$environmentToken} is not numeric");
            }

            return (int)$environmentToken;
        }

        return mt_rand(1 * pow(10, $this->length - 1), str_repeat(9, $this->length));
    }

    protected function getDefaultEnvironmentToken(): ?string
    {
        $token = getenv('TOKEN_GENERATOR_DEFAULT');

        if (!is_string($token) || mb_strlen($token) < $this->length) {
            return null;
        }

        return mb_substr($token, 0, $this->length);
    }
}
