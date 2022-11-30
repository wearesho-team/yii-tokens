<?php

declare(strict_types=1);

namespace Wearesho\Yii\Services;

use Wearesho\Yii\Interfaces\TokenGeneratorInterface;

class TokenGenerator implements TokenGeneratorInterface
{
    protected int $length;

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

    protected function getDefaultEnvironmentToken(): ?string
    {
        $token = getenv('TOKEN_GENERATOR_DEFAULT');

        if (!is_string($token) || mb_strlen($token) < $this->length) {
            return null;
        }

        return mb_substr($token, 0, $this->length);
    }
}
