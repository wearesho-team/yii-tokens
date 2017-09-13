<?php


namespace Wearesho\Yii\Queries;


use Carbon\Carbon;
use Carbon\CarbonInterval;

use Wearesho\Yii\Interfaces\TokenQueryInterface;
use Wearesho\Yii\Models\RegistrationToken;

use yii\db\ActiveQuery;

/**
 * Class RegistrationTokenQuery
 * @package Wearesho\Yii\Models
 * @see RegistrationToken
 */
class RegistrationTokenQuery extends ActiveQuery implements TokenQueryInterface
{
    /**
     * RegistrationTokenQuery constructor.
     * @param string $modelClass
     * @param array $config
     */
    public function __construct($modelClass = RegistrationToken::class, array $config = [])
    {
        parent::__construct($modelClass, $config);
    }

    /**
     * @param CarbonInterval $expirePeriod
     * @return TokenQueryInterface
     */
    public function notExpired(CarbonInterval $expirePeriod): TokenQueryInterface
    {
        return $this->andWhere(['>=',
            'created_at',
            Carbon::now()->sub($expirePeriod)->toDateTimeString()
        ]);
    }

    /**
     * @param string $recipient
     * @return TokenQueryInterface
     */
    public function whereRecipient(string $recipient): TokenQueryInterface
    {
        return $this->andWhere(['=', 'recipient', $recipient]);
    }
}