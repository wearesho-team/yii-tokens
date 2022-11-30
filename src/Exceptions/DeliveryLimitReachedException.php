<?php

declare(strict_types=1);

namespace Wearesho\Yii\Exceptions;

use Carbon\Carbon;
use Carbon\CarbonInterval;

class DeliveryLimitReachedException extends TokenException
{
    protected CarbonInterval $timeout;

    protected int $attempts;

    public function __construct(int $attempts, CarbonInterval $timeout, $code = 0, \Throwable $previous = null)
    {
        $message = "Delivery limit of {$attempts} reached. Try after "
            . Carbon::now()->add($timeout)->toDateTimeString();
        parent::__construct($message, $code, $previous);

        $this->attempts = $attempts;
        $this->timeout = $timeout;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function getTimeout(): CarbonInterval
    {
        return $this->timeout;
    }
}
