<?php


namespace Wearesho\Yii\Exceptions;


use Carbon\Carbon;
use Carbon\CarbonInterval;
use Throwable;

/**
 * Class DeliveryLimitReachedException
 * @package Wearesho\Yii\Exceptions
 */
class DeliveryLimitReachedException extends RegistrationException
{
    /** @var  CarbonInterval */
    protected $timeout;

    /** @var  int */
    protected $attempts;

    public function __construct(int $attempts, CarbonInterval $timeout, $code = 0, Throwable $previous = null)
    {
        $message = "Delivery limit of {$attempts} reached. Try after "
            . Carbon::now()->add($timeout)->toDateTimeString();
        parent::__construct($message, $code, $previous);

        $this->attempts = $attempts;
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->attempts;
    }

    /**
     * @return CarbonInterval
     */
    public function getTimeout(): CarbonInterval
    {
        return $this->timeout;
    }
}