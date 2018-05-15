<?php

namespace Wearesho\Yii\Traits;

/**
 * Trait TokenText
 * @package Wearesho\Yii\Traits
 *
 * @method getToken()
 */
trait TokenText
{
    public function getText(): string
    {
        return $this->getToken();
    }
}
