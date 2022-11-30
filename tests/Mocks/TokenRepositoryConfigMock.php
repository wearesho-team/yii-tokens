<?php

declare(strict_types=1);

namespace Wearesho\Yii\Tests\Mocks;

use Carbon\CarbonInterval;

use Wearesho\Yii\Interfaces\TokenRepositoryConfigInterface;

class TokenRepositoryConfigMock implements TokenRepositoryConfigInterface
{
    protected CarbonInterval $expirePeriod;

    protected int $verifyLimit = 3;

    protected int $deliveryLimit = 3;

    public function __construct()
    {
        $this->expirePeriod = CarbonInterval::hour();
    }

    public function getExpirePeriod(): CarbonInterval
    {
        return $this->expirePeriod;
    }

    public function getVerifyLimit(): int
    {
        return $this->verifyLimit;
    }

    public function getDeliveryLimit(): int
    {
        return $this->deliveryLimit;
    }

    public function setExpirePeriod(CarbonInterval $expirePeriod)
    {
        $this->expirePeriod = $expirePeriod;
    }

    public function setDeliveryLimit(int $deliveryLimit)
    {
        $this->deliveryLimit = $deliveryLimit;
    }

    public function setVerifyLimit(int $verifyLimit)
    {
        $this->verifyLimit = $verifyLimit;
    }
}
