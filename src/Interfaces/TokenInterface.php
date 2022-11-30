<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

use Wearesho\Delivery;

interface TokenInterface extends Delivery\MessageInterface
{
    /**
     * Token recipients (for example phone or email)
     */
    public function getRecipient(): string;

    public function getToken(): string;

    /**
     * Additional registration fields
     */
    public function getData(): array;

    /**
     * Count of sent attempts
     */
    public function getDeliveryCount(): int;

    /**
     * Count of verify attempts
     */
    public function getVerifyCount(): int;

    public function __toString(): string;
}
