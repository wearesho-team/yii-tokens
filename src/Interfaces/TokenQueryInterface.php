<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

use Carbon\CarbonInterval;

use yii\db\ActiveQueryInterface;

interface TokenQueryInterface extends ActiveQueryInterface
{
    public function notExpired(CarbonInterval $expirePeriod): TokenQueryInterface;

    public function whereRecipient(string $recipient): TokenQueryInterface;
}
