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

    /**
     * TokenGenerator constructor.
     * @param int $length
     */
    public function __construct(int $length = 6)
    {
        $this->length = $length;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        $numbers = range(0, 9);

        return implode('', array_map(function () use (&$numbers) {
            return array_rand($numbers);
        }, range(1, $this->length)));
    }
}