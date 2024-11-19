<?php

namespace Wearesho\Yii\Queries;

use Carbon\Carbon;
use Carbon\CarbonInterval;

use Wearesho\Yii\Interfaces\TokenQueryInterface;
use Wearesho\Yii\Models\Token;

use yii\db\ActiveQuery;

/**
 * @see Token
 */
class TokenQuery extends ActiveQuery implements TokenQueryInterface
{

    public function notExpired(CarbonInterval $expirePeriod): TokenQueryInterface
    {
        return $this->andWhere(['>=',
            'created_at',
            Carbon::now()->sub($expirePeriod)->toDateTimeString()
        ]);
    }

    public function whereRecipient(string $recipient): TokenQueryInterface
    {
        return $this->andWhere(['=', 'recipient', $recipient]);
    }
}
