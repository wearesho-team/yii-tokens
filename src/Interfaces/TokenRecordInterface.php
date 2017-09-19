<?php

namespace Wearesho\Yii\Interfaces;

use yii\db\ActiveRecordInterface;

/**
 * Interface TokenRecordInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenRecordInterface extends TokenInterface, ActiveRecordInterface
{
    /**
     * @return string
     */
    public static function getType(): string;

    /**
     * @param string $token
     * @return TokenRecordInterface
     */
    public function setToken(string $token): TokenRecordInterface;

    /**
     * @param string $recipient
     * @return TokenRecordInterface
     */
    public function setRecipient(string $recipient): TokenRecordInterface;

    /**
     * @param array $data
     * @return TokenRecordInterface
     */
    public function setData(array $data): TokenRecordInterface;

    /**
     * @return TokenRecordInterface
     */
    public function increaseVerifyCount(): TokenRecordInterface;

    /**
     * @return TokenRecordInterface
     */
    public function increaseDeliveryCount(): TokenRecordInterface;

    /**
     * @return TokenQueryInterface
     */
    public static function find(): TokenQueryInterface;
}