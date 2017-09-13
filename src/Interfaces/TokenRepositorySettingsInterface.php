<?php


namespace Wearesho\Yii\Interfaces;


use Carbon\CarbonInterval;

/**
 * Interface TokenRepositorySettingsInterface
 * @package Wearesho\Yii\Interfaces
 */
interface TokenRepositorySettingsInterface
{
    /**
     * @return CarbonInterval
     */
    public function getExpirePeriod(): CarbonInterval;

    /**
     * @return int
     */
    public function getVerifyLimit(): int;

    /**
     * @return int
     */
    public function getDeliveryLimit(): int;
}