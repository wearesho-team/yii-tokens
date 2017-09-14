<?php


namespace Wearesho\Yii\Interfaces;

use Carbon\CarbonInterval;

use yii\db\ActiveQueryInterface;

/**
 * Interface TokenQueryInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenQueryInterface extends ActiveQueryInterface
{
    /**
     * @param CarbonInterval $expirePeriod
     * @return TokenQueryInterface
     */
    public function notExpired(CarbonInterval $expirePeriod): TokenQueryInterface;

    /**
     * @param string $recipient
     * @return TokenQueryInterface
     */
    public function whereRecipient(string $recipient): TokenQueryInterface;
}