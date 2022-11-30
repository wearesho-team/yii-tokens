<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

use Carbon\CarbonInterval;

interface TokenRepositoryConfigInterface
{
    public function getExpirePeriod(): CarbonInterval;

    public function getVerifyLimit(): int;

    public function getDeliveryLimit(): int;
}
