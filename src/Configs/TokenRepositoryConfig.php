<?php


namespace Wearesho\Yii\Configs;

use Carbon\CarbonInterval;

use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;

use yii\base\BaseObject;
use yii\base\Configurable;

/**
 * Class TokenRepositorySettings
 * @package Wearesho\Yii\Configs
 */
class TokenRepositoryConfig extends BaseObject implements TokenRepositoryConfigInterface, Configurable
{
    public $expirePeriodKey = 'TOKEN_EXPIRE_MINUTES';
    public $verifyLimitKey = 'TOKEN_VERIFY_LIMIT';
    public $deliveryLimitKey = 'TOKEN_DELIVERY_LIMIT';

    public $defaultExpirePeriod = 30;
    public $defaultVerifyLimit = 3;
    public $defaultDeliveryLimit = 3;

    /**
     * @return CarbonInterval
     */
    public function getExpirePeriod(): CarbonInterval
    {
        $minutes = getenv($this->expirePeriodKey) ?: $this->defaultExpirePeriod;
        return CarbonInterval::minutes((int) $minutes);
    }

    /**
     * @return int
     */
    public function getVerifyLimit(): int
    {
        return getenv($this->verifyLimitKey) ?: $this->defaultVerifyLimit;
    }

    /**
     * @return int
     */
    public function getDeliveryLimit(): int
    {
        return getenv($this->deliveryLimitKey) ?: $this->defaultDeliveryLimit;
    }
}
