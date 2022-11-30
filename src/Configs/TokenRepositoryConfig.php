<?php

declare(strict_types=1);

namespace Wearesho\Yii\Configs;

use Carbon\CarbonInterval;

use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;

use yii\base\BaseObject;
use yii\base\Configurable;

class TokenRepositoryConfig extends BaseObject implements TokenRepositoryConfigInterface, Configurable
{
    public string $expirePeriodKey = 'TOKEN_EXPIRE_MINUTES';
    public string $verifyLimitKey = 'TOKEN_VERIFY_LIMIT';
    public string $deliveryLimitKey = 'TOKEN_DELIVERY_LIMIT';

    public int $defaultExpirePeriod = 30;
    public int $defaultVerifyLimit = 3;
    public int $defaultDeliveryLimit = 3;

    public function getExpirePeriod(): CarbonInterval
    {
        $minutes = (int)getenv($this->expirePeriodKey) ?: $this->defaultExpirePeriod;
        return CarbonInterval::minutes((int) $minutes);
    }

    public function getVerifyLimit(): int
    {
        return (int)getenv($this->verifyLimitKey) ?: $this->defaultVerifyLimit;
    }

    public function getDeliveryLimit(): int
    {
        return (int)getenv($this->deliveryLimitKey) ?: $this->defaultDeliveryLimit;
    }
}
