<?php

declare(strict_types=1);

namespace Wearesho\Yii\Interfaces;

interface TokenGeneratorInterface
{
    public function getToken(): string;
}
