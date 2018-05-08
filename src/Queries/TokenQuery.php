<?php


namespace Wearesho\Yii\Queries;

use Carbon\Carbon;
use Carbon\CarbonInterval;

use Wearesho\Yii\Interfaces\TokenQueryInterface;
use Wearesho\Yii\Models\Token;

use yii\db\ActiveQuery;

/**
 * Class TokenQuery
 * @package Wearesho\Yii\Models
 * @see Token
 */
class TokenQuery extends ActiveQuery implements TokenQueryInterface
{
    /**
     * RegistrationTokenQuery constructor.
     * @param string $modelClass
     * @param array $config
     *
     * @see Token::getType()
     */
    public function __construct($modelClass, array $config = [])
    {
        parent::__construct($modelClass, $config);
        $this->where(['=', 'type', call_user_func([$modelClass, 'getType'])]);
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
