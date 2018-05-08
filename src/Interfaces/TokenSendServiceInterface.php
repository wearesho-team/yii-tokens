<?php


namespace Wearesho\Yii\Interfaces;

/**
 * Interface TokenSendServiceInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenSendServiceInterface
{
    /**
     * @param TokenInterface $token
     * @return bool
     */
    public function send(TokenInterface $token): bool;
}
