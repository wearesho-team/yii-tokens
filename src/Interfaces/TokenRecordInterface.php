<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

use yii\db\ActiveRecordInterface;

interface TokenRecordInterface extends TokenInterface, ActiveRecordInterface
{
    public static function getType(): string;

    public function setToken(string $token): TokenRecordInterface;

    public function setRecipient(string $recipient): TokenRecordInterface;

    public function setData(array $data): TokenRecordInterface;

    public function increaseVerifyCount(): TokenRecordInterface;

    public function increaseDeliveryCount(): TokenRecordInterface;

    public static function find(): TokenQueryInterface;
}
