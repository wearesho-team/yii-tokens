<?php


namespace Wearesho\Yii\Interfaces;

/**
 * Interface TokenInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenInterface
{
    /**
     * Token recipients (for example phone or email)
     *
     * @return string
     */
    public function getRecipient(): string;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * Additional registration fields
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Count of sent attempts
     *
     * @return int
     */
    public function getDeliveryCount(): int;

    /**
     * Count of verify attempts
     *
     * @return int
     */
    public function getVerifyCount(): int;

    public function __toString(): string;
}
