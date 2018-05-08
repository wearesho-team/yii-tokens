<?php


namespace Wearesho\Yii\Interfaces;

/**
 * Interface TokenGeneratorInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenGeneratorInterface
{
    public function getToken(): string;
}
