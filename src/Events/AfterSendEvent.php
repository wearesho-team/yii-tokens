<?php

declare(strict_types=1);

namespace Wearesho\Yii\Events;

use Wearesho\Yii\Interfaces\TokenInterface;
use yii\base;

/**
 * If this event is handled then TokenRepository shall increase delivery count (event if main delivery failed)
 */
class AfterSendEvent extends base\Event
{
    public const NAME = 'afterSend';

    public function __construct(
        public readonly TokenInterface $token,
        array $config = []
    ) {
        parent::__construct($config);
    }
}
